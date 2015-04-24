<?php
namespace TacticianModule\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
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
        /** @var CommandBus $commandBus */
        $commandBus = $serviceLocator->get(CommandBus::class);

        return new TacticianCommandBusPlugin($commandBus);
    }
}
