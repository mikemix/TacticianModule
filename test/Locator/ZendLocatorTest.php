<?php
namespace TacticianModuleTest\Locator;

use League\Tactician\Exception\MissingHandlerException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Container\ContainerInterface;
use stdClass;
use TacticianModule\Locator\ZendLocator;

class ZendLocatorTest extends TestCase
{
    /**
     * @var ContainerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var ZendLocator
     */
    private $locator;

    public function setUp()
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();

        $this->locator = new ZendLocator($this->container);
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingCommandHandler()
    {
        $this->container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => []
                ],
            ]));

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('command');
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingServiceNameAndMissingClass()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => [
                        'command' => 'handler',
                    ]
                ],
            ]));

        $this->container->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('handler'))
            ->will($this->returnValue(false));

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('command');
    }

    public function testGetHandlerForCommandShouldAllowFQCN()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => [
                        'command' => stdClass::class,
                    ]
                ],
            ]));

        $this->container->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo(stdClass::class))
            ->will($this->returnValue(false));

        $this->assertInstanceOf(stdClass::class, $this->locator->getHandlerForCommand('command'));
    }

    public function testGetHandlerForCommandShouldReturnValidHandler()
    {
        $handler = new stdClass();

        $this->container->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => [
                        'command' => stdClass::class,
                    ]
                ],
            ]));

        $this->container->expects($this->at(1))
            ->method('has')
            ->will($this->returnValue(true));

        $this->container->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo(stdClass::class))
            ->will($this->returnValue($handler));

        $this->assertSame($handler, $this->locator->getHandlerForCommand('command'));
    }
}
