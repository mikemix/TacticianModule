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
        $this->tacticianCommandBus()->execute($command);
    }
}
```

If you need to inject the command bus into your service layer or similar, then simply grab from the __Service Manager__ using the FQCN of the `HandlerExecutionCommandBus`.

```
namespace MyNamespace;

use League\Tactician\CommandBus\HandlerExecutionCommandBus;
use Zend\ServiceManager\ServiceManager;
use MyNamespace\Service\MyService;

class Module.php
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                MyService::class => function(ServiceManager $serviceManager) {
                    $commandBus = $serviceManager->get(HandlerExecutionCommandBus::class);
                    return new MyService($commandBus);
                },
            ],
        ];
    }
}
```

### Configuring

The module ships with a `InMemoryLocator` and a `HandlerExecutionCommandBus` configured as default. If you wish to override the default locator or default command bus implementations, then simply use the `tactician` key in the merged config.

```
'tactician' => [
    'default-locator' => InMemoryLocator::class,
    'default-command-bus' => HandlerExecutionCommandBus::class,
    'commandbus-handlers' => [],
],
```

The `default-locator` key tells the module which locator to use as default (its a service manager key). The `default-command-bus` key tells the module which command bus to use by default (its also a service manager key). You can overwrite these in any module that is loaded after the `TacticianModule` (ie. comes __after__ `TacticianModule` in `application.config.php`).

The `commandbus-handlers` is a key/value pair array of commands to handlers that are registered via the default locator. These handlers are automatically registered in the locator factory. So if you wanted to register your own `UserLoginCommandHander` to handle `UserLoginCommand`s in your own `module.config.php`:

```
return [

    ... other configuration here
    
    'tactician' => [
        'commandbus-handlers' => [
            UserLoginCommand::class => UserLoginCommandHandler::class
        ]
    ],
];
```