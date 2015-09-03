<?php

namespace Mailer\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NotificationEventListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $config = $serviceLocator->get('config');
        $config = $config['mailer']['notification'];

        if(empty($config['transport'])){
            throw new Exception\RuntimeException('You need to configure notification transport.');
        }
        if(empty($config['mailSettings']['defaultFrom'])){
            throw new Exception\RuntimeException('You need to configure a default sender email.');
        }

        $listener = new \Mailer\Service\NotificationEventListener($serviceLocator->get($config['transport']), $config['mailSettings']);

        if(!empty($config['log'])){
            $listener->setLog($serviceLocator->get($config['log']));
        } else {
            $listener->setLog(new \Zend\Log\Logger());
        }
        $listener->setServiceLocator($serviceLocator);

        return $listener;
    }
}