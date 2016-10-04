<?php

return array(
    'bjyauthorize' => array(

        'identity_provider'  => \BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider::class,

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            \BjyAuthorize\Provider\Role\ObjectRepositoryProvider::class => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'User\Entity\Role',
            ),
        ),

        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'adminPanel' => array(),
                'userData' => array(),
                'cartPanel' => array()
            ),
        ),

         'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array( array('user', 'guest'), 'cartPanel'),
                    array( array('admin'), 'adminPanel' ),
                    array( array('admin'), 'userData' ),
                ),
            ),
        ),

        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            \BjyAuthorize\Guard\Controller::class => array(
                array(
                    'controller' => array('Item'),
                    'action' => array('index', 'view'),
                    'roles' => array('user', 'guest', 'admin')
                ),
                array(
                    'controller' => 'Item',
                    'action' => array('add', 'edit', 'delete'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Order',
                    'action' => array('addItem', 'removeItem', 'updateItem', 'currentOrder', 'add'),
                    'roles' => array('guest')
                ),
                array(
                    'controller' => 'Order',
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Order',
                    'action' => array('index', 'view'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'zfcuser',
                    'roles' => array('guest', 'user', 'admin')
                )
            ),
        ),
    ),
);
