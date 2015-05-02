# Tactician ZF2 Module
## Wrapper module for easy use of the [Tactician](http://tactician.thephpleague.com/) Command Bus in your ZF2 applications.

### Using

The module presents a __Controller Plugin__ called `tacticianCommandBus()` for easy use of dispatching commands. 
 
```php

// real life example
// imports skipped for brevity

class LoginController extends AbstractActionController
{
    /** @var LoginForm */
    protected $form;

    // constructor code skipped for brevity

    public function indexAction()
    {
        if ($this->request->isPost()) {
            $this->form->setData($this->request->getPost());
            if ($this->form->isValid()) {
                $command = new UserLoggedCommand();
                $command->login = $this->form->getLogin();
                $command->password = $this->form->getPassword();
                
                $this->tacticianCommandBus($command);
                return $this->redirect()->toRoute('home');
            }
        }
    
        $view = new ViewModel();
        $view->setVariable('form', $this->form);
        $view->setTemplate('app/login/index');
        
        return $view;
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

class Module.php
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

