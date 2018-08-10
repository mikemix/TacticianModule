<?php
namespace TacticianModule\Factory;

use League\Tactician\Handler\Locator\InMemoryLocator;
use Psr\Container\ContainerInterface;

class InMemoryLocatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return InMemoryLocator
     */
    public function __invoke(ContainerInterface $container)
    {
        $handlerMap = $container->get('config')['tactician']['handler-map'];

        return new InMemoryLocator($handlerMap);
    }
}
