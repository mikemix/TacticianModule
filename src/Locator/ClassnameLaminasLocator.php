<?php
namespace TacticianModule\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Psr\Container\ContainerInterface;

class ClassnameLaminasLocator implements HandlerLocator
{
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
        $handlerFQCN = $commandName . 'Handler';

        if ($this->container->has($handlerFQCN)) {
            return $this->container->get($handlerFQCN);
        }

        if (class_exists($handlerFQCN)) {
            return new $handlerFQCN();
        }

        throw MissingHandlerException::forCommand($commandName);
    }
}
