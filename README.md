# Tactician ZF2 Module

[![Build Status](https://travis-ci.org/mikemix/TacticianModule.svg?branch=master)](https://travis-ci.org/mikemix/TacticianModule) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mikemix/TacticianModule/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mikemix/TacticianModule/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/mikemix/TacticianModule/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mikemix/TacticianModule/?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/556b5a106365320026fa4500/badge.svg?style=flat)](https://www.versioneye.com/user/projects/556b5a106365320026fa4500) [![Latest Stable Version](https://poser.pugx.org/mikemix/tactician-module/v/stable)](https://packagist.org/packages/mikemix/tactician-module) [![Total Downloads](https://poser.pugx.org/mikemix/tactician-module/downloads)](https://packagist.org/packages/mikemix/tactician-module) [![License](https://poser.pugx.org/mikemix/tactician-module/license)](https://packagist.org/packages/mikemix/tactician-module)

## Wrapper module for easy use of the [Tactician](http://tactician.thephpleague.com/) Command Bus in your ZF2 applications.

### Installation

Best install with Composer:

`php composer.phar require mikemix/tactician-module:~1.0`

### Register as ZF2 module inside your ```config/application.config.php``` file:

```php
    'modules' => [
        'YourApplicationModule',
        'TacticianModule',
    ],
```

### Using

The module presents a __Controller Plugin__ called `tacticianCommandBus()` for easy use of dispatching commands. If no command object is passed to it, the CommandBus object will be returned. If you pass the command however, it will be passed over to the CommandBus and handled, and the output from the handler will be returned.

You can type hint this plugin in your controller, for example: ```@method \League\Tactician\CommandBus|mixed tacticianCommandBus(object $command)```.

```php

// Real life example.
// Namespaces, imports, class properties skipped for brevity

class LoginController extends AbstractActionController
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $this->form->setData($this->request->getPost());

            if ($this->form->isValid()) {
                $command = new UserLoginCommand(
                    $this->form->getLogin(),
                    $this->form->getPassword()
                ));

                if ($this->tacticianCommandBus($command)) {
                    // if handler returned true
                    // user was logged in successfully
                    return $this->redirect()->toRoute('home');
                } else {
                    $this->flashMessenger()->addErrorMessage('Invalid username or password');
                    return $this->redirect()->refresh();
                }
            }
        }

        $view = new ViewModel();
        $view->setVariable('form', $this->form);
        $view->setTemplate('app/login/index');

        return $view;
    }
}

final class UserLoginCommand
{
    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }
}

final class UserLoginHandler
{
    public function handle(UserLoginCommand $command)
    {
        return $this->authenticationService->login($command->username, $command->password);
    }
}
```

If you need to inject the command bus into your service layer or similar, then simply grab from the __Service Manager__ using the FQCN of the `CommandBus`.

```php
<?php
namespace MyNamespace;

use League\Tactician\CommandBus;
use Zend\ServiceManager\ServiceManager;
use MyNamespace\Service\MyService;

class Module
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                MyService::class => function(ServiceManager $serviceManager) {
                    $commandBus = $serviceManager->get(CommandBus::class);
                    return new MyService($commandBus);
                },
            ],
        ];
    }
}
```

### Configuring

The module ships with a `ZendLocator` and a `CommandHandlerMiddleware` and a `HandlerInflector` configured as default. If you wish to override the default locator or default command bus implementations, then simply use the `tactician` key in the merged config.

```php
'tactician' => [
    'default-extractor'  => ClassNameExtractor::class,
    'default-locator'    => ZendLocator::class,
    'default-inflector'  => HandleInflector::class,
    'handler-map'        => [],
    'middleware'         => [
        CommandHandlerMiddleware::class => 0,
    ],
],
```

`default-extractor`, `default-locator` and `default-inflector` are service manager keys to registered services.

`ZendLocator` expects handlers in the `handler-map` to be `commandName => serviceManagerKey` or `commandName => Handler_FQCN`, altough to prevent additional costly checks, use serviceManagerKey and make sure it is available as a Zend Service.

To add custom middleware to the middleware stack, add it to the `middleware` array as `ServiceName` => `priority` in which the middleware are supposed to be executed (higher the number, earlier it will execute). For example

```php
// ... your module config
'tactician' => [
    'middleware'         => [
        YourCustomMiddleware::class  => -100, // execute last
        YourAnotherMiddleware::class => 100, // execute early
    ],
],
```

### Basic usage

Basicly, all you probably will want to do, is to define the `handler-map` array in your module's configuration. For example:

```php
// module.config.php file

    return [
        // other keys
        'tactician' => [
            'handler-map' => [
                App\Command\SomeCommand::class => App\Handler\SomeCommandHandler::class,
            ],
        ],
    ];
```

### Changing the Handler Locator

### ClassnameZendLocator

This locator simply appends the word `Handler` to the command's FQCN so no you don't have to define any handler map. For example, if you request command `App\Commands\LoginCommand`, locator will try to fetch `App\Command\LoginCommandHandler` from the Service Manager.

Locator will work with FQCN's not registered in the Service Manager, altough to prevent additional costly checks, make sure your locator is registered as a invokable or factory.
