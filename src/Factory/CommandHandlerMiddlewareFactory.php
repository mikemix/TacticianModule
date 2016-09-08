<?php
namespace TacticianModule\Factory;

use Interop\Container\ContainerInterface;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Zend\ServiceManager\Factory\FactoryInterface;

class CommandHandlerMiddlewareFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return CommandHandlerMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['tactician'];

        /** @var CommandNameExtractor $extractor */
        $extractor = $container->get($config['default-extractor']);

        /** @var HandlerLocator $locator */
        $locator = $container->get($config['default-locator']);

        /** @var MethodNameInflector $inflector */
        $inflector = $container->get($config['default-inflector']);

        return new CommandHandlerMiddleware($extractor, $locator, $inflector);
    }
}
