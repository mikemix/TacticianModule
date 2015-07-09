<?php
namespace TacticianModule\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ZendLocator implements HandlerLocator, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /** @var array */
    protected $handlerMap;

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
        if (!$this->commandExists($commandName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        $serviceNameOrFQCN = $this->handlerMap[$commandName];

        try {
            return $this->getServiceLocator()->get($serviceNameOrFQCN);
        } catch (ServiceNotFoundException $e) {
            // Further check exists for class availability.
            // If not, Exception will be thrown anyway.
        }

        if (class_exists($serviceNameOrFQCN)) {
            return new $serviceNameOrFQCN();
        }

        throw MissingHandlerException::forCommand($commandName);
    }

    /**
     * @param string $commandName
     * @return bool
     */
    protected function commandExists($commandName)
    {
        if (!$this->handlerMap) {
            $this->handlerMap = $this->getServiceLocator()->get('config')['tactician']['handler-map'];
        }

        return isset($this->handlerMap[$commandName]);
    }
}
