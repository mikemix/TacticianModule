# Tactician ZF3 Module

[![Build Status](https://travis-ci.org/mikemix/TacticianModule.svg?branch=master)](https://travis-ci.org/mikemix/TacticianModule) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mikemix/TacticianModule/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mikemix/TacticianModule/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/mikemix/TacticianModule/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mikemix/TacticianModule/?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/556b5a106365320026fa4500/badge.svg?style=flat)](https://www.versioneye.com/user/projects/556b5a106365320026fa4500) [![Latest Stable Version](https://poser.pugx.org/mikemix/tactician-module/v/stable)](https://packagist.org/packages/mikemix/tactician-module) [![Total Downloads](https://poser.pugx.org/mikemix/tactician-module/downloads)](https://packagist.org/packages/mikemix/tactician-module) [![License](https://poser.pugx.org/mikemix/tactician-module/license)](https://packagist.org/packages/mikemix/tactician-module)

## Wrapper module for easy use of the [Tactician](http://tactician.thephpleague.com/) Command Bus in your ZF3 applications.

### Installation

Best install with Composer:

`composer require mikemix/tactician-module`

### Register as Zend Framework module inside your ```config/application.config.php``` file:

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

                try {
                    $this->tacticianCommandBus($command);
                    return $this->redirect()->toRoute('home');
                } catch (\Some\Kind\Of\Login\Failure $exception) {
                    $this->flashMessenger()->addErrorMessage($exception->getMessage());
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
    // constructor skipped for brevity

    public function handle(UserLoginCommand $command)
    {
        $this->authenticationService->login($command->username, $command->password);
    }
}
```

You can inject the `CommandBus` into yout service layer through a factory by simply requesting the `League\Tactician\CommandBus::class` from the __Container__.

### Configuring

The module ships with a `ZendLocator` and a `CommandHandlerMiddleware` and a `HandlerInflector` configured as default. If you wish to override the default locator or default command bus implementations, then simply use the `tactician` key in the merged config.

```php
'tactician' => [
    'default-extractor'  => League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor::class,
    'default-locator'    => TacticianModule\Locator\ZendLocator::class,
    'default-inflector'  => League\Tactician\Handler\HandleInflector::class,
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

### Plugins

#### LockingMiddleware

The [LockingMiddleware](http://tactician.thephpleague.com/plugins/locking-middleware/) can now be used out of the box.
Simply add the `League\Tactician\Plugins\LockingMiddleware` FQCN to the TacticianModule's middleware configuration with
appropriate priority. You probably want to execute it before the `CommandHandlerMiddleware`:

```php
// module.config.php file

    return [
        // other keys
        'tactician' => [
            'middleware' => [
                \League\Tactician\Plugins\LockingMiddleware::class => 500,
            ],
        ],
    ];
```

#### TransactionMiddleware

The [TransactionMiddleware](http://tactician.thephpleague.com/plugins/doctrine/) can now be used out of the box.
Simply add the `League\Tactician\Doctrine\ORM\TransactionMiddleware` FQCN to the TacticianModule's middleware configuration with
appropriate priority. You probably want to execute it before the `CommandHandlerMiddleware` and after the `LockingMiddleware`:

```php
// module.config.php file

    return [
        // other keys
        'tactician' => [
            'middleware' => [
                \League\Tactician\Doctrine\ORM\TransactionMiddleware::class => 250,
            ],
        ],
    ];
```

### Changing the Handler Locator

#### ClassnameZendLocator

This locator simply appends the word `Handler` to the command's FQCN so you don't have to define any handler map. For example, if you request command `App\Commands\LoginCommand`, locator will try to get `App\Command\LoginCommandHandler` from the Service Manager.

Locator will work with FQCN's not registered in the Service Manager, altough to prevent additional costly checks, make sure the locator is registered as a invokable or factory.

To change the locator from ZendLocator to ClassnameZendLocator simply set it in the config:

```php
// ... your module config
'tactician' => [
    'default-locator' => TacticianModule\Locator\ClassnameZendLocator::class,
],
```
