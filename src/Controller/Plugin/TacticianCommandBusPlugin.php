<?php
namespace TacticianModule\Controller\Plugin;

use League\Tactician\CommandBus;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class TacticianCommandBusPlugin extends AbstractPlugin
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke($command = null)
    {
        if (!$command) {
            return $this->commandBus;
        }
        
        return $this->commandBus->handle($command);
    }
}
