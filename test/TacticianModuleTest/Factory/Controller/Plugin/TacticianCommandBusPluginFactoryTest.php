<?php
namespace TacticianModuleTest\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use TacticianModule\Factory\Controller\Plugin\TacticianCommandBusPluginFactory;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\AbstractPluginManager;

class TacticianCommandBusPluginFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateServiceWithAbstractPluginManager()
    {
        $commandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceManager|\PHPUnit_Framework_MockObject_MockObject $sm */
        $sm = $this->getMockBuilder(ServiceManager::class)
            ->setMethods(['get'])
            ->getMock();

        $sm->expects($this->once())
            ->method('get')
            ->with($this->equalTo(CommandBus::class))
            ->will($this->returnValue($commandBus));

        /** @var AbstractPluginManager|\PHPUnit_Framework_MockObject_MockObject $pluginManager */
        $pluginManager = $this->getMockBuilder(AbstractPluginManager::class)
            ->setMethods(['getServiceLocator'])
            ->getMockForAbstractClass();

        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->will($this->returnValue($sm));

        $factory = new TacticianCommandBusPluginFactory();
        $this->assertInstanceOf(TacticianCommandBusPlugin::class, $factory->createService($pluginManager));
    }

    public function testCreateServiceWithServiceManager()
    {
        $commandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceManager|\PHPUnit_Framework_MockObject_MockObject $sm */
        $sm = $this->getMock(ServiceManager::class, ['get']);
        $sm->expects($this->once())
            ->method('get')
            ->with($this->equalTo(CommandBus::class))
            ->will($this->returnValue($commandBus));

        $factory = new TacticianCommandBusPluginFactory();
        $this->assertInstanceOf(TacticianCommandBusPlugin::class, $factory->createService($sm));
    }
}
