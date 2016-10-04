<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Item' => 'Item\Controller\ItemController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'paginator'               => __DIR__ . '/../view/partial/paginator.phtml',
            'breadcrumbs'               => __DIR__ . '/../view/partial/breadcrumbs.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),

    'view_helpers' => array(
        'invokables' => array(
            'ShowMessages' => 'Item\View\Helper\ShowMessages'
        )
    ),

    'controller_plugins' => array(
        'factories' => array(
            'Cart' => 'Cart\Factory\CartFactory',
        ),
    )
);
