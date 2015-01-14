<?php
/**
 * Created by Gary Hockin.
 * Date: 13/01/15
 * @GeeH
 */

namespace TacticianModule\Factory;

use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TacticianCommandBusPluginFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return TacticianCommandBusPlugin
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();
        $config = $serviceManager->get('config')['tactician'];

        $commandBus = $serviceManager->get($config['default-command-bus']);

        return new TacticianCommandBusPlugin($commandBus);
    }
}
