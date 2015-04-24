<?php
namespace TacticianModuleTest\Factory;

use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use TacticianModule\Factory\CommandHandlerMiddlewareFactory;
use Zend\ServiceManager\ServiceManager;

class CommandHandlerMiddlewareFactoryTest extends \PHPUnit_Framework_TestCase
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
            ->getMockForAbstractClass();

        $locatorStub = $this->getMockBuilder(HandlerLocator::class)
            ->getMockForAbstractClass();

        $inflectorStub = $this->getMockBuilder(MethodNameInflector::class)
            ->getMockForAbstractClass();

        $serviceLocator->setService('extractor-service', $extractorStub);
        $serviceLocator->setService('locator-service', $locatorStub);
        $serviceLocator->setService('inflector-service', $inflectorStub);


        $factory = new CommandHandlerMiddlewareFactory();
        $handler = $factory->createService($serviceLocator);

        $this->assertInstanceOf(CommandHandlerMiddleware::class, $handler);
    }
}
