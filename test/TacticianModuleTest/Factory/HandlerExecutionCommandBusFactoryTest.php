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
use Zend\ServiceManager\ServiceLocatorInterface;

class HandlerExecutionCommandBusFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {

        $defaultLocator = uniqid();

        $config         = [
            'tactician' => [
                'default-locator'     => $defaultLocator,
                'default-command-bus' => HandlerExecutionCommandBus::class,
                'commandbus-handlers' => [],
            ],
        ];
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);

        $serviceLocator->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap([
                    ['config', $config],
                    [$defaultLocator, new InMemoryLocator()]
                ])
            );

        $factory = new HandlerExecutionCommandBusFactory();
        $this->assertInstanceOf(HandlerExecutionCommandBus::class, $factory->createService($serviceLocator));
    }
}
