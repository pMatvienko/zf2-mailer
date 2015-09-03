<?php
namespace Mailer;

use Zend\EventManager\EventInterface;
use Zend\EventManager\StaticEventManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;

class Module implements AutoloaderProviderInterface, BootstrapListenerInterface
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
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Mailer\Form\Notification' => function($sm) {
                    return new Form\NotificationForm('notification-form', array('serviceLocator' => $sm));
                }
            )
        );
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
//        \Zend\Debug\Debug::dump($e->getApplication()->getServiceManager()->get('Mailer/Notification/Transport'));
//        exit();
    }
}
