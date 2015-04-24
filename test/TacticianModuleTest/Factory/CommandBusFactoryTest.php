<?php
namespace TacticianModuleTest\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use TacticianModule\Factory\CommandBusFactory;
use TacticianModuleTest\Middleware\CustomMiddleware;
use Zend\ServiceManager\ServiceManager;

class CommandBusFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ServiceManager */
    private $serviceLocator;

    /** @var CommandBusFactory */
    private $factory;

    public function setUp()
    {
        $this->serviceLocator = new ServiceManager();

        $this->factory = new CommandBusFactory();
    }

    public function testCreateServiceWithNoMiddleware()
    {
        $this->serviceLocator->setService('config', [
            'tactician' => [
                'middleware' => []
            ]
        ]);

        $this->assertInstanceOf(CommandBus::class, $this->factory->createService($this->serviceLocator));
    }

    public function testCreateServiceWithOnlyOneMiddleware()
    {
        $command = new \stdClass();

        $middlewareStub = $this->getMockBuilder(Middleware::class)
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $middlewareStub->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($command));

        $this->serviceLocator->setService('config', [
            'tactician' => [
                'middleware' => [
                    'middleware-service' => $middlewareStub
                ]
            ]
        ]);

        $this->serviceLocator->setService('middleware-service', $middlewareStub);

        $commandBus = $this->factory->createService($this->serviceLocator);
        $commandBus->handle($command);
    }

    public function testCreateServiceWithMiddlewarePrioritized()
    {
        $middlewareStubEarly = new CustomMiddleware('1');
        $middlewareStubMiddle = new CustomMiddleware('2');
        $middlewareStubLast = new CustomMiddleware('3');

        $this->serviceLocator->setService('config', [
            'tactician' => [
                'middleware' => [
                    'middleware-middle' => 0,
                    'middleware-early'  => 100,
                    'middleware-last'   => -100,
                ]
            ]
        ]);

        $this->serviceLocator->setService('middleware-early', $middlewareStubEarly);
        $this->serviceLocator->setService('middleware-middle', $middlewareStubMiddle);
        $this->serviceLocator->setService('middleware-last', $middlewareStubLast);

        $commandBus = $this->factory->createService($this->serviceLocator);
        $output = $commandBus->handle(new \stdClass());

        $this->assertEquals('123', $output);
    }
}
