<?php

namespace TacticianModule\Locator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClassnameZendLocatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ClassnameZendLocator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ClassnameZendLocator($serviceLocator);
    }
}
