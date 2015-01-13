<?php
/**
 * Created by Gary Hockin.
 * Date: 12/01/15
 * @GeeH
 */

namespace TacticianModuleTest\Factory;


use League\Tactician\CommandBus\Handler\Locator\InMemoryLocator;
use TacticianModule\Factory\InMemoryLocatorFactory;
use Zend\ServiceManager\ServiceManager;

class InMemoryLocatorFactoryTest extends \PHPUnit_Framework_TestCase
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
        $serviceLocator->expects($this->any())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $factory = new InMemoryLocatorFactory();
        $this->assertInstanceOf(InMemoryLocator::class, $factory->createService($serviceLocator));
    }
}