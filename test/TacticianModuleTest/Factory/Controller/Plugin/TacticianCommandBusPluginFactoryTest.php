<?php
namespace TacticianModuleTest\Factory\Controller\Plugin;

use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use TacticianModule\Factory\Controller\Plugin\TacticianCommandBusPluginFactory;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceManager;

class TacticianCommandBusPluginFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $commandBusStub = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceManager|\PHPUnit_Framework_MockObject_MockObject $serviceLocator */
        $serviceLocator = $this->getMockBuilder(ServiceManager::class)
            ->setMethods(['get'])
            ->getMock();

        $serviceLocator->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->equalTo(CommandBus::class))
            ->will($this->returnValue($commandBusStub));

        /** @var PluginManager|\PHPUnit_Framework_MockObject_MockObject $pluginManager */
        $pluginManager = $this->getMockBuilder(PluginManager::class)
            ->setMethods(['getServiceLocator'])
            ->getMock();

        $pluginManager->expects($this->atLeastOnce())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceLocator));


        $factory = new TacticianCommandBusPluginFactory();
        $this->assertInstanceOf(TacticianCommandBusPlugin::class, $factory->createService($pluginManager));
    }
}
