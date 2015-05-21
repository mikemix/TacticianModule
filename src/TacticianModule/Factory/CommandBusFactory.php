<?php
namespace TacticianModule\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
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

        arsort($configMiddleware);
        
        $list = [];
        foreach ($configMiddleware as $serviceName => $priority) {
            /** @var Middleware $middleware */
            $middleware = $serviceLocator->get($serviceName);
            $list[] = $middleware;
        }

        return new CommandBus($list);
    }
}
