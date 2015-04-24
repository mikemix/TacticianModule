<?php

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use TacticianModule\Factory\CommandBusFactory;
use TacticianModule\Factory\CommandHandlerMiddlewareFactory;
use TacticianModule\Factory\Controller\Plugin\TacticianCommandBusPluginFactory;
use TacticianModule\Factory\InMemoryLocatorFactory;
use TacticianModule\Locator\ZendLocator;

return [
    'service_manager' => [
        'invokables' => [
            ClassNameExtractor::class => ClassNameExtractor::class,
            HandleInflector::class => HandleInflector::class,
            ZendLocator::class => ZendLocator::class,
        ],
        'factories' => [
            CommandBus::class => CommandBusFactory::class,
            CommandHandlerMiddleware::class => CommandHandlerMiddlewareFactory::class,
            InMemoryLocator::class => InMemoryLocatorFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'tacticianCommandBus' => TacticianCommandBusPluginFactory::class,
        ],
    ],
    'tactician' => [
        'default-extractor'  => ClassNameExtractor::class,
        'default-locator'    => ZendLocator::class,
        'default-inflector'  => HandleInflector::class,
        'handler-map'        => [],
        'middleware'         => [
            CommandHandlerMiddleware::class => 0,
        ],
    ],
];
