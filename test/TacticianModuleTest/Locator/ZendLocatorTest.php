<?php
namespace TacticianModuleTest\Locator;

use League\Tactician\Exception\MissingHandlerException;
use TacticianModule\Locator\ZendLocator;
use Zend\ServiceManager\ServiceManager;

class ZendLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceLocator;

    /**
     * @var ZendLocator
     */
    protected $locator;

    public function setUp()
    {
        $this->serviceLocator = new ServiceManager();

        $this->locator = new ZendLocator();
        $this->locator->setServiceLocator($this->serviceLocator);
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingCommandHandler()
    {
        $this->serviceLocator->setService('config', [
            'tactician' => [
                'handler-map' => []
            ],
        ]);

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('command');
    }

    public function testGetHandlerForCommandShouldThrowExceptionOnMissingServiceName()
    {
        $this->serviceLocator->setService('config', [
            'tactician' => [
                'handler-map' => [
                    'command' => 'handler',
                ]
            ],
        ]);

        $this->setExpectedException(MissingHandlerException::class);
        $this->locator->getHandlerForCommand('command');
    }

    public function testGetHandlerForCommandShouldAllowFQCN()
    {
        $this->serviceLocator->setService('config', [
            'tactician' => [
                'handler-map' => [
                    'command' => ZendLocatorTest::class,
                ]
            ],
        ]);

        $this->assertInstanceOf(ZendLocatorTest::class, $this->locator->getHandlerForCommand('command'));
    }
}
