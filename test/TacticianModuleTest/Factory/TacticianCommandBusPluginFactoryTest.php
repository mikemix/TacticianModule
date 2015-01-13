<?php
/**
 * Created by Gary Hockin.
 * Date: 13/01/15
 * @GeeH
 */

namespace TacticianModuleTest\Factory;


use League\Tactician\CommandBus\Handler\Locator\InMemoryLocator;
use League\Tactician\CommandBus\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\CommandBus\HandlerExecutionCommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use TacticianModule\Factory\TacticianCommandBusPluginFactory;
use Zend\Mvc\Controller\PluginManager;

class TacticianCommandBusPluginFactoryTest extends \PHPUnit_Framework_TestCase
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
            ->with(HandlerExecutionCommandBus::class)
            ->willReturn(new HandlerExecutionCommandBus(new InMemoryLocator(), new HandleClassNameInflector()));

        $pluginManager = $this->getMockBuilder(PluginManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getServiceLocator'])
            ->getMock();
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($serviceLocator);


        $factory = new TacticianCommandBusPluginFactory();

        $this->assertInstanceOf(TacticianCommandBusPlugin::class, $factory->createService($pluginManager));
    }
}
