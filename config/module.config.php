<?php

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Plugins\LockingMiddleware;
use TacticianModule\Factory\CommandBusFactory;
use TacticianModule\Factory\CommandHandlerMiddlewareFactory;
use TacticianModule\Factory\Controller\Plugin\TacticianCommandBusPluginFactory;
use TacticianModule\Factory\InMemoryLocatorFactory;
use TacticianModule\Factory\Plugin\DoctrineTransactionFactory;
use TacticianModule\Locator\ClassnameLaminasLocator;
use TacticianModule\Locator\ClassnameLaminasLocatorFactory;
use TacticianModule\Locator\LaminasLocator;
use TacticianModule\Locator\LaminasLocatorFactory;

return [
    'service_manager' => [
        'invokables' => [
            ClassNameExtractor::class   => ClassNameExtractor::class,
            HandleInflector::class      => HandleInflector::class,
            LockingMiddleware::class    => LockingMiddleware::class,
            InvokeInflector::class      => InvokeInflector::class
        ],
        'factories' => [
            CommandBus::class               => CommandBusFactory::class,
            CommandHandlerMiddleware::class => CommandHandlerMiddlewareFactory::class,
            InMemoryLocator::class          => InMemoryLocatorFactory::class,
            ClassnameLaminasLocator::class     => ClassnameLaminasLocatorFactory::class,
            LaminasLocator::class              => LaminasLocatorFactory::class,
            'League\Tactician\Doctrine\ORM\TransactionMiddleware' => DoctrineTransactionFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'tacticianCommandBus' => TacticianCommandBusPluginFactory::class,
        ],
    ],
    'tactician' => [
        'default-extractor'  => ClassNameExtractor::class,
        'default-locator'    => LaminasLocator::class,
        'default-inflector'  => HandleInflector::class,
        'handler-map'        => [],
        'plugins'            => [
            'League\Tactician\Doctrine\ORM\TransactionMiddleware' => 'Doctrine\ORM\EntityManager',
        ],
        'middleware'         => [
            CommandHandlerMiddleware::class => 0,
        ],
    ],
];
