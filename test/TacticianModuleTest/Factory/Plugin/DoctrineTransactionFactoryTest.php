<?php
namespace TacticianModuleTest\Factory\Plugin;

use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use TacticianModule\Factory\Plugin\DoctrineTransactionFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineTransactionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ServiceLocatorInterface */
    private $serviceLocator;

    /** @var DoctrineTransactionFactory */
    private $factory;

    public function setUp()
    {
        $this->serviceLocator = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $this->factory = new DoctrineTransactionFactory();
    }

    public function testCreateService()
    {
        $this->serviceLocator->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'plugins' => [
                        TransactionMiddleware::class => 'doctrine.orm.orm_default'
                    ]
                ],
        ]));

        $doctrineStub = $this->getMockBuilder(\Doctrine\ORM\EntityManagerInterface::class)
            ->getMock();

        $this->serviceLocator->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('doctrine.orm.orm_default'))
            ->will($this->returnValue($doctrineStub));

        $this->assertInstanceOf(
            TransactionMiddleware::class,
            $this->factory->__invoke($this->serviceLocator, TransactionMiddleware::class)
        );
    }
}
