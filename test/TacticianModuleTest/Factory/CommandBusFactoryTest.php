<?php
namespace TacticianModuleTest\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use TacticianModule\Factory\CommandBusFactory;
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
        $command = new \stdClass();

        $middlewareStubEarly = new CustomMiddleware('early');
        $middlewareStubMiddle = new CustomMiddleware('middle');
        $middlewareStubLast = new CustomMiddleware('last');

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
        $commandBus->handle($command);

        $this->assertEquals(['early', 'middle', 'last'], CustomMiddleware::$output);
    }
}

class CustomMiddleware implements Middleware
{
    public static $output = [];
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * When executed, just append its name to the output array.
     * @param object $command
     * @param callable $next
     * @return mixed|null
     */
    public function execute($command, callable $next)
    {
        self::$output[] = $this->name;
        $next($command);

        return null;
    }
}
