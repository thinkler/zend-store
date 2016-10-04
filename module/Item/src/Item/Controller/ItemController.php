<?php

namespace Item\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Item\Entity\Item;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class ItemController extends AbstractActionController
{
    protected $em;

    private function getEntityManager()
    {
        if (null == $this->em) {
            $this->em = $this->getServiceLocator()
                             ->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction()
    {
        if ($this->Cart()->isReady() && $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('order', array('action' => 'add'));
        }

        return new ViewModel(array(
            'paginator' => $this->paginateItems()
        ));
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $item = $this->getEntityManager()
                     ->getRepository('Item\Entity\Item')
                     ->findOneBy(array('id' => $id));

        if (!$item) {
            return $this->redirect()->toRoute('item');
        }

        return new ViewModel(array(
            'item' => $item->getArrayCopy()
        ));
    }

    public function addAction()
    {
        $form = new \Item\Form\ItemForm();
        $form->get('submit')->setValue('Create');
        $request = $this->getRequest();
        $viewModel = new ViewModel(array('form' => $form));

        if (! $request->isPost()) {
            return $viewModel;
        }

        $form->setData($request->getPost()->toArray());

        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage("Check fields");
            return $viewModel;
        }

        $item = new \Item\Entity\Item;
        $item->exchangeArray($form->getData());

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
        $this->flashMessenger()->addMessage("Item created");

        return $this->redirect()->toRoute('item');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new \Item\Form\ItemForm();
        $form->get('submit')->setValue('Edit');
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $item = $this->findItem($id);
            $form->bind($item);
            return array('form' => $form, 'id' => $id, 'item' => $item);
        } else {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $objectManager = $this->getEntityManager();
                $data = $form->getData();
                $id = $data['id'];

                $item = $this->findItem($id);
                $item->exchangeArray($form->getData());
                $objectManager->persist($item);
                $objectManager->flush();
                $message = 'Item succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                return $this->redirect()->toRoute('item');
            } else {
                $message = 'Error while saving item.';
                $this->flashMessenger()->addErrorMessage($message);
                return array('form' => $form, 'id' => $id);
            }
        }
    }

    public function deleteAction()
    {
        $objectManager = $this->getEntityManager();
        $id = (int) $this->params()->fromRoute('id', 0);
        $item = $this->findItem($id);
        $objectManager->remove($item);
        $objectManager->flush();
        $this->flashMessenger()->addMessage(sprintf('Item was succesfully deleted.', $id));

        return $this->redirect()->toRoute('item');
    }

    private function paginateItems($perPage = 9)
    {
       $repository = $this->getEntityManager()->getRepository('Item\Entity\Item');
       $adapter = new DoctrineAdapter(new ORMPaginator($repository->createQueryBuilder('item')));
       $paginator = new Paginator($adapter);
       $paginator->setDefaultItemCountPerPage($perPage);

       $page = (int)$this->params()->fromQuery('page');
       if($page) $paginator->setCurrentPageNumber($page);

       return $paginator;
    }

    private function findItem($id) {
        $objectManager = $this->getEntityManager();
        $item = $objectManager->find('\Item\Entity\Item', $id);
        if (!$item) {
            $this->flashMessenger()->addErrorMessage('Operation error');
            return $this->redirect()->toRoute('item');
        } else {
            return $item;
        }
    }
}
