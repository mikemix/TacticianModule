<?php

chdir(dirname(__DIR__));

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require 'vendor/autoload.php';

if (! $loader) {
    throw new Exception('No Autoloading setup');
}

$loader->add('TacticianModuleTest\\', __DIR__);
