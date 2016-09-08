<?php

namespace TacticianModule\Locator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ZendLocatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return ZendLocator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ZendLocator($container);
    }
}
