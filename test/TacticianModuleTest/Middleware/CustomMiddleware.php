<?php
namespace TacticianModuleTest\Middleware;

use League\Tactician\Middleware;

class CustomMiddleware implements Middleware
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * When executed, just append its name to the output array.
     * @param object $command
     * @param callable $next
     * @return mixed|null
     */
    public function execute($command, callable $next)
    {
        $output = $this->name . $next($command);
        return $output;
    }
}
