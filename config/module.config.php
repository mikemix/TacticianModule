<?php
use League\Tactician\CommandBus\Handler\Locator\InMemoryLocator;
use League\Tactician\CommandBus\HandlerExecutionCommandBus;
use TacticianModule\Factory\HandlerExecutionCommandBusFactory;
use TacticianModule\Factory\InMemoryLocatorFactory;
use TacticianModule\Factory\TacticianCommandBusPluginFactory;

return [
    'service_manager' => [
        'factories' => [
            HandlerExecutionCommandBus::class => HandlerExecutionCommandBusFactory::class,
            InMemoryLocator::class => InMemoryLocatorFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'tacticianCommandBus' => TacticianCommandBusPluginFactory::class,
        ],
    ],
    'tactician' => [
        'default-locator' => InMemoryLocator::class,
        'default-command-bus' => HandlerExecutionCommandBus::class,
        'commandbus-handlers' => [],
    ],
];
