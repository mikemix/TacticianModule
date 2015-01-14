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
use Zend\ServiceManager\ServiceLocatorInterface;

class TacticianCommandBusPluginFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $handlerClass = uniqid();

        $config         = [
            'tactician' => [
                'default-locator'     => InMemoryLocator::class,
                'default-command-bus' => $handlerClass,
                'commandbus-handlers' => [],
            ],
        ];
        $serviceLocator = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->getMock();
        $serviceLocator->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                ['config', $config],
                [$handlerClass, new HandlerExecutionCommandBus(new InMemoryLocator(), new HandleClassNameInflector())]
            ]));

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
