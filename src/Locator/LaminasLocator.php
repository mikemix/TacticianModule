<?php
namespace TacticianModule\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Psr\Container\ContainerInterface;

class LaminasLocator implements HandlerLocator
{
    /** @var array */
    protected $handlerMap;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        if (!$this->commandExists($commandName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        $serviceNameOrFQCN = $this->handlerMap[$commandName];

        if ($this->container->has($serviceNameOrFQCN)) {
            return $this->container->get($serviceNameOrFQCN);
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
            $this->handlerMap = $this->container->get('config')['tactician']['handler-map'];
        }

        return isset($this->handlerMap[$commandName]);
    }
}
