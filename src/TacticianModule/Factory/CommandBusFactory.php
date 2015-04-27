<?php
namespace TacticianModule\Factory;

use League\Tactician\CommandBus;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\PriorityList;

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
        $middlewareCount = count($configMiddleware);

        if ($middlewareCount == 0) {
            return new CommandBus([]);
        }

        if ($middlewareCount == 1) {
            $serviceName = key($configMiddleware);
            return new CommandBus([$serviceLocator->get($serviceName)]);
        }

        arsort($configMiddleware);
        
        $middleware = [];
        foreach ($configMiddleware as $serviceName => $priority) {
            $middleware[] = $serviceLocator->get($serviceName);
        }

        return new CommandBus($middleware);
    }
}
