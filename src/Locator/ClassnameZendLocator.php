<?php
namespace TacticianModule\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ClassnameZendLocator implements HandlerLocator, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

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

        try {
            return $this->getServiceLocator()->get($handlerFQCN);
        } catch (ServiceNotFoundException $e) {
            // Further check exists for class availability.
            // If not, Exception will be thrown anyway.
        }

        if (class_exists($handlerFQCN)) {
            return new $handlerFQCN();
        }

        throw MissingHandlerException::forCommand($commandName);
    }
}
