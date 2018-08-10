<?php

namespace TacticianModule\Locator;

use Psr\Container\ContainerInterface;

class ClassnameZendLocatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ClassnameZendLocator
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ClassnameZendLocator($container);
    }
}
