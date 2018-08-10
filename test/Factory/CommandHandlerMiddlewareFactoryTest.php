<?php
namespace TacticianModuleTest\Factory;

use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use PHPUnit\Framework\TestCase;
use TacticianModule\Factory\CommandHandlerMiddlewareFactory;
use Zend\ServiceManager\ServiceManager;

class CommandHandlerMiddlewareFactoryTest extends TestCase
{
    public function testCreateService()
    {
        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('config', [
            'tactician' => [
                'default-extractor' => 'extractor-service',
                'default-locator'   => 'locator-service',
                'default-inflector' => 'inflector-service',
            ],
        ]);

        $extractorStub = $this->getMockBuilder(CommandNameExtractor::class)
            ->getMock();

        $locatorStub = $this->getMockBuilder(HandlerLocator::class)
            ->getMock();

        $inflectorStub = $this->getMockBuilder(MethodNameInflector::class)
            ->getMock();

        $serviceLocator->setService('extractor-service', $extractorStub);
        $serviceLocator->setService('locator-service', $locatorStub);
        $serviceLocator->setService('inflector-service', $inflectorStub);


        $factory = new CommandHandlerMiddlewareFactory();
        $handler = $factory($serviceLocator, CommandHandlerMiddleware::class);

        $this->assertInstanceOf(CommandHandlerMiddleware::class, $handler);
    }
}
