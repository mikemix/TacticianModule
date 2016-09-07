<?php
namespace TacticianModule\Factory\Plugin;

use Interop\Container\ContainerInterface;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use Zend\ServiceManager\Factory\FactoryInterface;

class DoctrineTransactionFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return TransactionMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['tactician']['plugins'];
        $ormKey = $config[TransactionMiddleware::class];

        return new TransactionMiddleware($container->get($ormKey));
    }
}
