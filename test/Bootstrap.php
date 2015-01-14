<?php
/** @var Composer\Autoload\ClassLoader $loader */
$loader = require('vendor/autoload.php');
if (!$loader) {
    throw new Exception('No Autoloading setup');
}
if (!array_key_exists('TacticianModule', $loader->getPrefixes())) {
    $loader->add('TacticianModule', './src');
}
