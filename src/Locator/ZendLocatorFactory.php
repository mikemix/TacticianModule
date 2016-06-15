<?php

namespace TacticianModule\Locator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZendLocatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ZendLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ZendLocator($serviceLocator);
    }
}