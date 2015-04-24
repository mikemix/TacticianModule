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
}
