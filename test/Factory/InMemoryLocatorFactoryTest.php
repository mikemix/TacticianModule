<?php
namespace TacticianModuleTest\Factory;

use League\Tactician\Handler\Locator\InMemoryLocator;
use PHPUnit\Framework\TestCase;
use TacticianModule\Factory\InMemoryLocatorFactory;
use Zend\ServiceManager\ServiceManager;

class InMemoryLocatorFactoryTest extends TestCase
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
                'handler-map' => []
            ],
        ]);

        $this->factory = new InMemoryLocatorFactory();
    }

    public function testCreateService()
    {
        $locator = $this->factory->__invoke(
            $this->serviceLocator,
            InMemoryLocator::class
        );

        $this->assertInstanceOf(InMemoryLocator::class, $locator);
    }
}
