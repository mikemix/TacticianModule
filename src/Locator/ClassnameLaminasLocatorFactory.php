<?php

namespace TacticianModule\Locator;

use Psr\Container\ContainerInterface;

class ClassnameLaminasLocatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ClassnameLaminasLocator
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ClassnameLaminasLocator($container);
    }
}
