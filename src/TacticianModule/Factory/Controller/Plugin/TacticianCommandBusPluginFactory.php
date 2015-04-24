<?php
namespace TacticianModule\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TacticianCommandBusPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface|PluginManager $pm
     * @return TacticianCommandBusPlugin
     */
    public function createService(ServiceLocatorInterface $pm)
    {
        /** @var CommandBus $commandBus */
        $commandBus = $pm->getServiceLocator()->get(CommandBus::class);

        return new TacticianCommandBusPlugin($commandBus);
    }
}
