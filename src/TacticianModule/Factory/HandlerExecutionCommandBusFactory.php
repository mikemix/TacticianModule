<?php
/**
 * Created by Gary Hockin.
 * Date: 12/01/15
 * @GeeH
 */

namespace TacticianModule\Factory;

use League\Tactician\CommandBus\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\CommandBus\HandlerExecutionCommandBus;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HandlerExecutionCommandBusFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return HandlerExecutionCommandBus
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config')['tactician'];

        $locator = $serviceLocator->get($config['default-locator']);

        return new HandlerExecutionCommandBus($locator, new HandleClassNameInflector());

    }
}
