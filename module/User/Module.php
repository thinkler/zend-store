<?php

namespace User;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap( MvcEvent $e )
    {
        $this->setUserRole($e);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    private function setUserRole(MvcEvent $e)
    {
        $em = \Zend\EventManager\StaticEventManager::getInstance();
        $entityManager = $e->getApplication()->getServiceManager()
                           ->get('doctrine.entitymanager.orm_default');
        $role = $entityManager->getRepository('User\Entity\Role')
                              ->findOneBy(array('roleId' => 'user'));

        $em->attach('ZfcUser\Service\User', 'register', function($e) use ($role) {
            $user = $e->getParam('user');
            $user->addRole($role);
        });
    }
}
