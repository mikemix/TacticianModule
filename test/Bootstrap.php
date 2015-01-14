<?php
chdir('./../../');
/** @var Composer\Autoload\ClassLoader $loader */
$loader = require('vendor/autoload.php');
if (!array_key_exists('TacticianModule', $loader->getPrefixes())) {
    $loader->add('TacticianModule', './module/TacticianModule/src');
}

if(!$loader) {
    throw new Exception('No Autoloading setup');
}
