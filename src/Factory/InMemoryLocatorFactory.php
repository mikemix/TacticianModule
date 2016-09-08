<?php
namespace TacticianModule\Factory;

use Interop\Container\ContainerInterface;
use League\Tactician\Handler\Locator\InMemoryLocator;
use Zend\ServiceManager\Factory\FactoryInterface;

class InMemoryLocatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return InMemoryLocator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $handlerMap = $container->get('config')['tactician']['handler-map'];

        return new InMemoryLocator($handlerMap);
    }
}
