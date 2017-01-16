<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Rss;

return array(
        'session' => array(
                'config' => array(
                        'class' => 'Zend\Session\Config\SessionConfig',
                        'options' => array(
                                'name' => 'zfdemo',
                                'remember_me_seconds' => 2419200,
                                'use_cookies' => true,
                                'cookie_httponly' => true,
                        ),
                ),
                'storage' => 'Zend\Session\Storage\SessionArrayStorage',
                'validators' => array(
                        'Zend\Session\Validator\RemoteAddr',
                        'Zend\Session\Validator\HttpUserAgent',
                ),
        ),
        'router' => array(
                'routes' => array(
                        'home' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                        'route' => '/',
                                        'defaults' => array(
                                                'controller' => 'Rss\Controller\Index',
                                                'action' => 'index',
                                        ),
                                ),
                        ),
                        'favorite' => array(
                                'type' => 'Segment',
                                'options' => array(
                                        'route' => '/favorite/:category',
                                        'defaults' => array(
                                                'controller' => 'Rss\Controller\Index',
                                                'action' => 'favorite',
                                        ),
                                        'constraints' => array(
                                                'category' => '[a-zA-Z]+'
                                        )
                                ),
                        ),
                        'category' => array(
                                'type' => 'Segment',
                                'options' => array(
                                        'route' => '/category/:category',
                                        'defaults' => array(
                                                'controller' => 'Rss\Controller\Index',
                                                'action' => 'index',
                                        ),
                                        'constraints' => array(
                                                'category' => '[a-zA-Z]+'
                                        )
                                ),
                        ),
                        // The following is a route to simplify getting started creating
                        // new controllers and actions without needing to create a new
                        // module. Simply drop new controllers in, and you can access them
                        // using the path /rss/:controller/:action
                        'rss' => array(
                                'type' => 'Literal',
                                'options' => array(
                                        'route' => '/rss',
                                        'defaults' => array(
                                                '__NAMESPACE__' => 'Rss\Controller',
                                                //'controller'    => 'Index',
                                                'controller' => 'Rss\Controller\Index',
                                                'action' => 'index',
                                        ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                        'default' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                        'route' => '/[:controller[/:action]]',
                                                        'constraints' => array(
                                                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                        ),
                                                        'defaults' => array(
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
        ),
        'service_manager' => array(
                'abstract_factories' => array(
                        'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
                        'Zend\Log\LoggerAbstractServiceFactory',
                ),
                'factories' => array(
                        'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
                ),
        ),
        'translator' => array(
                'locale' => 'en_US',
                'translation_file_patterns' => array(
                        array(
                                'type' => 'gettext',
                                'base_dir' => __DIR__ . '/../language',
                                'pattern' => '%s.mo',
                        ),
                ),
        ),
        'controllers' => array(
                'invokables' => array(
                        'Rss\Controller\Index' => 'Rss\Controller\IndexController'
                ),
        ),
        'view_manager' => array(
                'display_not_found_reason' => true,
                'display_exceptions' => true,
                'doctype' => 'HTML5',
                'not_found_template' => 'error/404',
                'exception_template' => 'error/index',
                'template_map' => array(
                        'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
                        'rss/index' => __DIR__ . '/../view/rss/index.phtml',
                        'error/404' => __DIR__ . '/../view/error/404.phtml',
                        'error/index' => __DIR__ . '/../view/error/index.phtml',
                ),
                'template_path_stack' => array(
                        __DIR__ . '/../view',
                ),
        ),
        // Placeholder for console routes
        'console' => array(
                'router' => array(
                        'routes' => array(
                        ),
                ),
        ),
);
