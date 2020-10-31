<?php
namespace TacticianModule\Controller\Plugin;

use League\Tactician\CommandBus;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

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

    /**
     * @param object|null $command
     * @return CommandBus|mixed
     */
    public function __invoke($command = null)
    {
        if ($command === null) {
            return $this->commandBus;
        }

        return $this->commandBus->handle($command);
    }
}
