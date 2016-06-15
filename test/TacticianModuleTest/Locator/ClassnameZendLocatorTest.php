<?php
namespace TacticianModuleTest\Locator;

use League\Tactician\Exception\MissingHandlerException;
use TacticianModule\Locator\ClassnameZendLocator;
use TestObjects\Command;
use TestObjects\CommandHandler;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ClassnameZendLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $serviceLocator;

    /**
     * @var ClassnameZendLocator
     */
    protected $locator;

    public function setUp()
    {
        $this->serviceLocator = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $this->locator = new ClassnameZendLocator($this->serviceLocator);
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingCommandHandler()
    {
        $this->serviceLocator->expects($this->once())
            ->method('get')
            ->with($this->equalTo('App\Command\SomeCommandHandler'))
            ->will($this->throwException(new ServiceNotFoundException()));

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('App\Command\SomeCommand');
    }

    public function testGetHandlerForCommandShouldAllowFQCN()
    {
        $this->serviceLocator->expects($this->once())
            ->method('get')
            ->with($this->equalTo(CommandHandler::class))
            ->will($this->throwException(new ServiceNotFoundException()));

        $this->assertInstanceOf(CommandHandler::class, $this->locator->getHandlerForCommand(Command::class));
    }

    public function testGetHandlerForCommandShouldReturnValidHandler()
    {
        $handler = new \stdClass();

        $this->serviceLocator->expects($this->once())
            ->method('get')
            ->with($this->equalTo(\stdClass::class . 'Handler'))
            ->will($this->returnValue($handler));

        $this->assertSame($handler, $this->locator->getHandlerForCommand(\stdClass::class));
    }
}
