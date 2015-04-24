<?php
namespace TacticianModuleTest\Controller\Plugin;

use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;

class TacticianCommandBusPluginTest extends \PHPUnit_Framework_TestCase
{
    public function testInvokeShouldReturnCommandBus()
    {
        /** @var CommandBus|\PHPUnit_Framework_MockObject_MockObject $commandBusMock */
        $commandBusMock = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        $plugin = new TacticianCommandBusPlugin($commandBusMock);

        $this->assertSame($commandBusMock, $plugin->__invoke());
    }

    public function testInvokeWithCommandShouldPassItToCommandBus()
    {
        $command = new \stdClass();

        /** @var CommandBus|\PHPUnit_Framework_MockObject_MockObject $commandBusMock */
        $commandBusMock = $this->getMockBuilder(CommandBus::class)
            ->setMethods(['handle'])
            ->disableOriginalConstructor()
            ->getMock();

        $commandBusMock->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($command));

        $plugin = new TacticianCommandBusPlugin($commandBusMock);
        $plugin->__invoke($command);
    }
}
