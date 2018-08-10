<?php
namespace TacticianModuleTest\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use PHPUnit\Framework\TestCase;
use stdClass;
use TacticianModule\Factory\CommandBusFactory;
use TacticianModuleTest\Middleware\CustomMiddleware;
use Zend\ServiceManager\ServiceManager;

class CommandBusFactoryTest extends TestCase
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

    public function testCreateServiceWithOneMiddleware()
    {
        $command = new stdClass();

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

        $commandBus = $this->factory->__invoke(
            $this->serviceLocator,
            CommandBus::class
        );
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

        $commandBus = $this->factory->__invoke(
            $this->serviceLocator,
            CommandBus::class
        );
        $output = $commandBus->handle(new stdClass());

        $this->assertEquals('123', $output);
    }
}
