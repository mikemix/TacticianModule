<?php

namespace TacticianModule\Locator;

use Psr\Container\ContainerInterface;

class LaminasLocatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return LaminasLocator
     */
    public function __invoke(ContainerInterface $container)
    {
        return new LaminasLocator($container);
    }
}
