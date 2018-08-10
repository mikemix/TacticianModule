<?php
namespace TacticianModule;

class ConfigProvider
{
    private $config;

    public function __construct()
    {
        $this->config = (new Module())->getConfig();
    }

    public function __invoke()
    {
        $config = $this->config;

        $config['dependencies'] = $config['service_manager'];

        return $config;
    }

    public function getDependencies()
    {
        return $this->config['service_manager'];
    }
}
