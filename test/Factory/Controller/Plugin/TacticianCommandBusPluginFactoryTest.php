<?php
namespace TacticianModuleTest\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Container\ContainerInterface;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use TacticianModule\Factory\Controller\Plugin\TacticianCommandBusPluginFactory;
use Zend\ServiceManager\ServiceManager;

class TacticianCommandBusPluginFactoryTest extends TestCase
{
    public function testCreateServiceWithContainer()
    {
        /** @var CommandBus|PHPUnit_Framework_MockObject_MockObject $commandBus */
        $commandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceManager|PHPUnit_Framework_MockObject_MockObject $sm */
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo(CommandBus::class))
            ->will($this->returnValue($commandBus));

        $factory = new TacticianCommandBusPluginFactory();
        $this->assertInstanceOf(
            TacticianCommandBusPlugin::class,
            $factory($container, TacticianCommandBusPlugin::class)
        );
    }
}
