<?php
namespace TacticianModuleTest;

use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use TacticianModule\Module;
use Zend\ServiceManager\ServiceManager;

class ModuleTest extends TestCase
{
    public function testConfig()
    {
        $module = new Module();
        $this->assertIsArray($module->getConfig());
    }

    public function testCreateCommandBusWithServiceManager()
    {
        $module = new Module();
        $moduleConfig = $module->getConfig();

        $serviceLocator = new ServiceManager($moduleConfig['service_manager']);
        $serviceLocator->setService('config', $moduleConfig);

        $this->assertInstanceOf(CommandBus::class, $serviceLocator->get(CommandBus::class));
    }
}
