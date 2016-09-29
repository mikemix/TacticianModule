<?php
namespace TacticianModule\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClassnameZendLocator implements HandlerLocator
{
    private $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieves the handler for a specified command
     *
     * @param string $commandName
     *
     * @return mixed
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerFQCN = $commandName . 'Handler';

        if ($this->serviceLocator->has($handlerFQCN)) {
            return $this->serviceLocator->get($handlerFQCN);
        }

        if (class_exists($handlerFQCN)) {
            return new $handlerFQCN();
        }

        throw MissingHandlerException::forCommand($commandName);
    }
}
