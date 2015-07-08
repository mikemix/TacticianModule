<?php
namespace TacticianModule\Factory;

use League\Tactician\Handler\Locator\InMemoryLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InMemoryLocatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return InMemoryLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $handlerMap = $serviceLocator->get('config')['tactician']['handler-map'];

        return new InMemoryLocator($handlerMap);
    }
}
