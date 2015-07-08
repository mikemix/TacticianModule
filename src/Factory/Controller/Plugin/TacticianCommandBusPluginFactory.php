<?php
namespace TacticianModule\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

class TacticianCommandBusPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $pm
     * @return TacticianCommandBusPlugin
     */
    public function createService(ServiceLocatorInterface $pm)
    {
        if ($pm instanceof AbstractPluginManager) {
            /** @var CommandBus $commandBus */
            $commandBus = $pm->getServiceLocator()->get(CommandBus::class);
        } else {
            /** @var CommandBus $commandBus */
            $commandBus = $pm->get(CommandBus::class);
        }

        return new TacticianCommandBusPlugin($commandBus);
    }
}
