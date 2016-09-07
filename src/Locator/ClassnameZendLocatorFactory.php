<?php

namespace TacticianModule\Locator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ClassnameZendLocatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return ClassnameZendLocator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ClassnameZendLocator($container);
    }
}
