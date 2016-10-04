<?php

namespace Order\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Order\Entity\Order;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class OrderController extends AbstractActionController
{
    protected $em;

    // Private

    private function getEntityManager()
    {
        if (null == $this->em) {
            $this->em = $this->getServiceLocator()
                             ->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    private function cartData()
    {
        return new JsonModel(array(
            'qty' => $this->Cart()->totalItems(),
            'sum' => $this->Cart()->totalSum()
        ));
    }

    private function generateOrder()
    {
        $itemsIds = [];

        foreach ($this->Cart()->cartItems() as $id => $item) {
            $itemsIds[] = $id;
        }

        $order = [];
        $order['quantity'] = $this->Cart()->totalItems();
        $order['totalPrice'] = $this->Cart()->totalSum();
        $order['itemsIds'] = serialize($itemsIds);

        return $order;
    }

    // Ajax

    public function addItemAction()
    {
        $request = $this->getRequest();
        $id = $request->getPost()->id;

        $item = $this->getEntityManager()
                     ->getRepository('Item\Entity\Item')
                     ->findOneBy(array('id' => $id));

        if (is_null($item)) {
            return;
        }

        $item->quantity = $request->getPost()->qty;

        $this->Cart()->insert($item);

        return $this->cartData();
    }

    public function removeItemAction()
    {
        $request = $this->getRequest();
        $id = $request->getPost()->id;

        if (!$this->Cart()->remove($id)) {
            return;
        }

        return $this->cartData();
    }

    public function updateItemAction()
    {
        $request = $this->getRequest();

        $quantity = $request->getPost()->qty;
        $id = $request->getPost()->id;

        if ($this->Cart()->update($id, $quantity)) {
            return $this->cartData();
        } else {
            return ;
        }
    }

    // Default

    public function currentOrderAction()
    {
        $itemsArray = [];

        foreach ($this->Cart()->cartItems() as $item) {
            $itemsArray[] = $item->getArrayCopy();
        }

        return new ViewModel(array(
            'items' => $itemsArray,
            'cart' => $this->Cart()
        ));
    }

    public function addAction()
    {
        $this->Cart()->setReady(true);

        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage("Please log in to save your order.");
            return $this->redirect()->toUrl('/user/login');
        }

        if (count($this->Cart()->cartItems()) == 0) {
            $this->flashMessenger()->addErrorMessage("Your order is empty.");
            return $this->redirect()->toUrl('/');
        }

        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();

        $order = new Order();
        $order->exchangeArray($this->generateOrder());
        $order->user = $this->getEntityManager()->getReference('\User\Entity\User', $userId);

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        $this->flashMessenger()->addMessage('Order saved.');
        $this->Cart()->clean();
        $this->Cart()->setReady(false);

        return $this->redirect()->toRoute('order', array('action' => 'indexUser'));
    }

    public function cleanCartAction()
    {
        $this->Cart()->clean();
        $this->Cart()->setReady(false);
        return $this->redirect()->toRoute('item');
    }

    public function indexUserAction()
    {

        $orders = $this->getEntityManager()
                       ->getRepository('Order\Entity\Order')
                       ->findBy(array('userId' => $this->zfcUserAuthentication()->getIdentity()->getId()));


        $ordersArary = [];

        foreach ($orders as $order) {
            $ordersArary[] = $order->getArrayCopy();
        }

        return new ViewModel(array(
            'orders' => $ordersArary
        ));
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'paginator' => $this->paginateOrders()
        ));
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $order = $this->getEntityManager()
                      ->getRepository('Order\Entity\Order')
                      ->findOneBy(array('id' => $id));

        if (!$order) {
            return $this->redirect()->toRoute('item');
        }

        $itemsArray = [];
        $itemsIds = unserialize($order->itemsIds);

        foreach ($itemsIds as $itemId) {
            $item = $this->getEntityManager()->find('\Item\Entity\Item', $itemId);
            $itemsArray[] = $item->getArrayCopy();
        }

        return new ViewModel(array(
            'order' => $order->getArrayCopy(),
            'items' => $itemsArray
        ));
    }

    private function paginateOrders($perPage = 10)
    {
       $repository = $this->getEntityManager()->getRepository('Order\Entity\Order');
       $adapter = new DoctrineAdapter(new ORMPaginator($repository->createQueryBuilder('orders')));
       $paginator = new Paginator($adapter);
       $paginator->setDefaultItemCountPerPage($perPage);

       $page = (int)$this->params()->fromQuery('page');
       if($page) $paginator->setCurrentPageNumber($page);

       return $paginator;
    }
}
