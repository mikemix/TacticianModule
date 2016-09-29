<?php
namespace TacticianModuleTest\Locator;

use League\Tactician\Exception\MissingHandlerException;
use TacticianModule\Locator\ZendLocator;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZendLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $serviceLocator;

    /**
     * @var ZendLocator
     */
    protected $locator;

    public function setUp()
    {
        $this->serviceLocator = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['get', 'has'])
            ->getMockForAbstractClass();

        $this->locator = new ZendLocator($this->serviceLocator);
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingCommandHandler()
    {
        $this->serviceLocator->expects($this->once())
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
        $this->serviceLocator->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => [
                        'command' => 'handler',
                    ]
                ],
            ]));

        $this->serviceLocator->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('handler'))
            ->will($this->returnValue(false));

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('command');
    }

    public function testGetHandlerForCommandShouldAllowFQCN()
    {
        $this->serviceLocator->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => [
                        'command' => \stdClass::class,
                    ]
                ],
            ]));

        $this->serviceLocator->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo(\stdClass::class))
            ->will($this->returnValue(false));

        $this->assertInstanceOf(\stdClass::class, $this->locator->getHandlerForCommand('command'));
    }

    public function testGetHandlerForCommandShouldReturnValidHandler()
    {
        $handler = new \stdClass();

        $this->serviceLocator->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'handler-map' => [
                        'command' => \stdClass::class,
                    ]
                ],
            ]));

        $this->serviceLocator->expects($this->at(1))
            ->method('has')
            ->will($this->returnValue(true));

        $this->serviceLocator->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo(\stdClass::class))
            ->will($this->returnValue($handler));

        $this->assertSame($handler, $this->locator->getHandlerForCommand('command'));
    }
}
