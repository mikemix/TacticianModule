<?php
namespace TacticianModuleTest\Factory\Plugin;

use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TacticianModule\Factory\Plugin\DoctrineTransactionFactory;

class DoctrineTransactionFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    private $container;

    /** @var DoctrineTransactionFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();

        $this->factory = new DoctrineTransactionFactory();
    }

    public function testCreateService()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('config'))
            ->will($this->returnValue([
                'tactician' => [
                    'plugins' => [
                        TransactionMiddleware::class => 'doctrine.orm.orm_default'
                    ]
                ],
        ]));

        $doctrineStub = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $this->container->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('doctrine.orm.orm_default'))
            ->will($this->returnValue($doctrineStub));

        $this->assertInstanceOf(
            TransactionMiddleware::class,
            $this->factory->__invoke($this->container, TransactionMiddleware::class)
        );
    }
}
