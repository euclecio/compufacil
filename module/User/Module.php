<?php

namespace User;

use User\Controller\UserController,
    User\Service\UserService,
    User\Controller\NewsController,
    User\Service\NewsService;

use Zend\Http\Response,
    Zend\Mvc\Controller\ControllerManager;

class Module
{
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
                )
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => [
                'User\Controller\User' => function (ControllerManager $cm) {
                    return new UserController($cm->getServiceLocator());
                },
                'User\Controller\News' => function (ControllerManager $cm) {
                    return new NewsController($cm->getServiceLocator());
                }
            ],
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'User\Service\UserService' => function($sm) {
                    return new UserService($sm->get('Doctrine\ORM\EntityManager'));
                },
                'User\Service\NewsService' => function($sm) {
                    return new NewsService($sm->get('Doctrine\ORM\EntityManager'));
                }
            )
        );
    }
}
