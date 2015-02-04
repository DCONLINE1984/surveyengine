<?php

/* 
 * The module bootstrap
 * @author    Dean Clow
 * @email     <deanrclow@gmail.com>
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.md', which is part of this source code package.
 */

namespace Api;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module
{
    public function getServiceConfig()
    {    
         return array(
            'factories' => array(
                'Api\Service\Survey' => function ($sm) {
                    $service = new \Api\Service\Survey($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Api\Service\Encoder\Survey());
                    $service->setModel(new \Api\Model\Survey());
                    return $service;
                },
                'Api\Service\Question' => function ($sm) {
                    $service = new \Api\Service\Question($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Api\Service\Encoder\Question());
                    $service->setModel(new \Api\Model\Question());
                    return $service;
                },
                'Api\Service\Page' => function ($sm) {
                    $service = new \Api\Service\Page($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Api\Service\Encoder\Page());
                    $service->setModel(new \Api\Model\Page());
                    return $service;
                },
                'Api\Service\Answer' => function ($sm) {
                    $service = new \Api\Service\Answer($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Api\Service\Encoder\Answer());
                    $service->setModel(new \Api\Model\Answer());
                    return $service;
                },
                'Api\Service\PageQuestion' => function ($sm) {
                    $service = new \Api\Service\PageQuestion($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Api\Service\Encoder\PageQuestion());
                    $service->setModel(new \Api\Model\PageQuestion());
                    return $service;
                },
            )
        );   
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $sharedEvents = $eventManager->getSharedManager();
        //when we start to add other modules, we can add the different layouts below
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            /* @var $e \Zend\Mvc\MvcEvent */
            // fired when an ActionController under the namespace is dispatched.
            $controller = $e->getTarget();
            $routeMatch = $e->getRouteMatch();
            /* @var $routeMatch \Zend\Mvc\Router\RouteMatch */
            $routeName = $routeMatch->getMatchedRouteName();
            $controller      = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config          = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
            //$controller->layout('layout/layout');
        }, 100);
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
}
