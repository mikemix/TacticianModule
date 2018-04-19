<?php
namespace TacticianModule\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use Psr\Container\ContainerInterface;

class CommandBusFactory
{
    /**
     * @param ContainerInterface $container
     * @return CommandBus
     */
    public function __invoke(ContainerInterface $container)
    {
        $configMiddleware = $container->get('config')['tactician']['middleware'];

        arsort($configMiddleware);

        $list = [];
        foreach (array_keys($configMiddleware) as $serviceName) {
            /** @var Middleware $middleware */
            $list[] = $container->get($serviceName);
        }

        return new CommandBus($list);
    }
}
