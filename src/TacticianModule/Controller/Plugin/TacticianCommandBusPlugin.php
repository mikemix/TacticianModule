<?php
/**
 * Created by Gary Hockin.
 * Date: 13/01/15
 * @GeeH
 */

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

    public function __invoke()
    {
        return $this->commandBus;
    }
}
