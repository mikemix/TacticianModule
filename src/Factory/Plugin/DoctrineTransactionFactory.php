<?php
namespace TacticianModule\Factory\Plugin;

use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use Psr\Container\ContainerInterface;

class DoctrineTransactionFactory
{
    /**
     * @param ContainerInterface $container
     * @return TransactionMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['tactician']['plugins'];
        $ormKey = $config[TransactionMiddleware::class];

        return new TransactionMiddleware($container->get($ormKey));
    }
}
