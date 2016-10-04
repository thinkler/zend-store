<?php

namespace Order;

return array(

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Order' => 'Order\Controller\OrderController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'order' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/order[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Order',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'order' => __DIR__ . '/../view',
        ),
        'strategies' => array(
           'ViewJsonStrategy',
        )
    ),

);
