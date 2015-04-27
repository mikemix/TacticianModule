# Tactician ZF2 Module
## Wrapper module for easy use of the [Tactician](http://tactician.thephpleague.com/) Command Bus in your ZF2 applications.

### Using

The module presents a __Controller Plugin__ called `tacticianCommandBus()` for easy use of dispatching commands. 
 
```
class MyController extends AbstractActionController
{
    public function indexAction()
    {
        $command = new UserLoginCommand();
        $command->login = 'crazylogin';
        
        $this->tacticianCommandBus($command);
    }
}
```

If you need to inject the command bus into your service layer or similar, then simply grab from the __Service Manager__ using the FQCN of the `CommandBus`.

```
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

```
'tactician' => [
    'default-extractor'  => ClassNameExtractor::class,
    'default-locator'    => InMemoryLocator::class,
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

```
// ... your module config
'tactician' => [
    'middleware'         => [
        YourCustomMiddleware::class  => -100, // execute last
        YourAnotherMiddleware::class => 100, // execute early
    ],
],
```

