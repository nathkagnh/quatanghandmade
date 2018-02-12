<?php
namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin-login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin/auth/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'admin-logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin/auth/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'admin-home' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/admin[/:action]',
                    'constraints' => [
                        'action' => '[0-9a-zA-Z_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'admin-product' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/admin/product[/:action]',
                    'constraints' => [
                        'action' => '[0-9a-zA-Z_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ProductController::class => InvokableFactory::class,
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\IndexController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                // ['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                ['actions' => ['index',], 'allow' => '@']
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'layout/admin' => __DIR__ . '/../view/layout/layout.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'route_layouts' => [
        'admin-*' => 'layout/admin',
    ],
];
