<?php
namespace TacticianModuleTest\Factory;

use TacticianModule\Factory\InMemoryLocatorFactory;
use Zend\ServiceManager\ServiceManager;

class InMemoryLocatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceLocator;

    /**
     * @var InMemoryLocatorFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('config', [
            'tactician' => [
                'handler-map' => [
                    'command' => 'handler',
                ]
            ],
        ]);

        $this->factory = new InMemoryLocatorFactory();
    }

    public function testCreateService()
    {
        $locator = $this->factory->createService($this->serviceLocator);

        $this->assertEquals('handler', $locator->getHandlerForCommand('command'));
    }
}
