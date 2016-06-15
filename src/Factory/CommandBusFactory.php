<?php
namespace TacticianModule\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandBusFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CommandBus
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configMiddleware = $serviceLocator->get('config')['tactician']['middleware'];

        arsort($configMiddleware);

        $list = [];
        foreach (array_keys($configMiddleware) as $serviceName) {
            /** @var Middleware $middleware */
            $list[] = $serviceLocator->get($serviceName);
        }

        return new CommandBus($list);
    }
}
