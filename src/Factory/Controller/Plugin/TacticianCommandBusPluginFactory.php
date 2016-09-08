<?php
namespace TacticianModule\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;

class TacticianCommandBusPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $pm
     * @param string $requestedName
     * @param array $options
     * @return TacticianCommandBusPlugin
     */
    public function __invoke(ContainerInterface $pm, $requestedName, array $options = null)
    {
        $commandBus = $pm->get(CommandBus::class);

        return new TacticianCommandBusPlugin($commandBus);
    }
}
