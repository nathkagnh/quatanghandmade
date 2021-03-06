<?php
namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'categories' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/the-loai/:theloai',
                    'constraints' => [
                        'theloai' => '[0-9a-zA-Z_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\CategoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'details' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/:theloai/:codename/:id',
                    'constraints' => [
                        'theloai' => '[0-9a-zA-Z_-]*',
                        'codename' => '[0-9a-zA-Z_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\DetailController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'shopping' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/shopping/:action',
                    'constraints' => [
                        'action' => '[0-9a-zA-Z_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\ShoppingController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\CategoryController::class => InvokableFactory::class,
            Controller\DetailController::class => InvokableFactory::class,
            Controller\ShoppingController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
