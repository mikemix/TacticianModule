<?php
namespace TacticianModule\Factory;

use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandHandlerMiddlewareFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CommandHandlerMiddleware
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config')['tactician'];

        /** @var CommandNameExtractor $extractor */
        $extractor = $serviceLocator->get($config['default-extractor']);

        /** @var HandlerLocator $locator */
        $locator = $serviceLocator->get($config['default-locator']);

        /** @var MethodNameInflector $inflector */
        $inflector = $serviceLocator->get($config['default-inflector']);

        return new CommandHandlerMiddleware($extractor, $locator, $inflector);
    }
}
