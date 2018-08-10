<?php

namespace TacticianModule\Locator;

use Psr\Container\ContainerInterface;

class ZendLocatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ZendLocator
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ZendLocator($container);
    }
}
