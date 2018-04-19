<?php
namespace TacticianModule\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;

class TacticianCommandBusPluginFactory
{
    /**
     * @param ContainerInterface $container
     * @return TacticianCommandBusPlugin
     */
    public function __invoke(ContainerInterface $container)
    {
        $commandBus = $container->get(CommandBus::class);

        return new TacticianCommandBusPlugin($commandBus);
    }
}
