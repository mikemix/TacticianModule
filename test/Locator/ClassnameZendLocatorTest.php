<?php
namespace TacticianModuleTest\Locator;

use League\Tactician\Exception\MissingHandlerException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Container\ContainerInterface;
use stdClass;
use TacticianModule\Locator\ClassnameZendLocator;
use TacticianModuleTest\TestObjects\Command;
use TacticianModuleTest\TestObjects\CommandHandler;

class ClassnameZendLocatorTest extends TestCase
{
    /**
     * @var ContainerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var ClassnameZendLocator
     */
    private $locator;

    public function setUp()
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();

        $this->locator = new ClassnameZendLocator($this->container);
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingCommandHandler()
    {
        $this->container->expects($this->once())
            ->method('has')
            ->with($this->equalTo('App\Command\SomeCommandHandler'))
            ->will($this->returnValue(false));

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('App\Command\SomeCommand');
    }

    public function testGetHandlerForCommandShouldAllowFQCN()
    {
        $this->container->expects($this->once())
            ->method('has')
            ->with($this->equalTo(CommandHandler::class))
            ->will($this->returnValue(false));

        $this->assertInstanceOf(CommandHandler::class, $this->locator->getHandlerForCommand(Command::class));
    }

    public function testGetHandlerForCommandShouldReturnValidHandler()
    {
        $handler = new stdClass();

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->equalTo(stdClass::class . 'Handler'))
            ->will($this->returnValue(true));

        $this->container->expects($this->once())
            ->method('get')
            ->with($this->equalTo(stdClass::class . 'Handler'))
            ->will($this->returnValue($handler));

        $this->assertSame($handler, $this->locator->getHandlerForCommand(stdClass::class));
    }
}
