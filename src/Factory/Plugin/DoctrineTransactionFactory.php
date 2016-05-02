<?php
namespace TacticianModule\Factory\Plugin;

use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineTransactionFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config')['tactician']['plugins'];
        $ormKey = $config[TransactionMiddleware::class];
        
        return new TransactionMiddleware($serviceLocator->get($ormKey));
    }
}