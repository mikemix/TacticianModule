<?php
namespace TacticianModuleTest;

use League\Tactician\CommandBus;
use TacticianModule\Module;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $module = new Module();

        $this->assertInternalType('array', $module->getConfig());
    }

    public function testCreateCommandBusWithServiceManager()
    {
        $module = new Module();
        $moduleConfig = $module->getConfig();

        $serviceLocator = new ServiceManager(new Config($moduleConfig['service_manager']));
        $serviceLocator->setService('config', $moduleConfig);

        $this->assertInstanceOf(CommandBus::class, $serviceLocator->get(CommandBus::class));
    }
}
