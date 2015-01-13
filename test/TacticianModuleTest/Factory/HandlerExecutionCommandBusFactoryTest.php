<?php
/**
 * Created by Gary Hockin.
 * Date: 12/01/15
 * @GeeH
 */

namespace TacticianModuleTest\Factory;


use League\Tactician\CommandBus\Handler\Locator\InMemoryLocator;
use League\Tactician\CommandBus\HandlerExecutionCommandBus;
use TacticianModule\Factory\HandlerExecutionCommandBusFactory;
use Zend\ServiceManager\ServiceManager;

class HandlerExecutionCommandBusFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config         = [
            'tactician' => [
                'default-locator'     => InMemoryLocator::class,
                'default-command-bus' => HandlerExecutionCommandBus::class,
                'commandbus-handlers' => [],
            ],
        ];
        $serviceLocator = $this->getMockBuilder(ServiceManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $serviceLocator->expects($this->at(0))
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $serviceLocator->expects($this->at(1))
            ->method('get')
            ->with(InMemoryLocator::class)
            ->willReturn(new InMemoryLocator());


        $factory = new HandlerExecutionCommandBusFactory();
        $this->assertInstanceOf(HandlerExecutionCommandBus::class, $factory->createService($serviceLocator));
    }
}
