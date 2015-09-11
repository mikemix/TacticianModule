<?php

chdir(dirname(__DIR__));
$autoloader = require 'vendor/autoload.php';
$autoloader->add('TacticianModuleTest\\', 'test/');
$autoloader->add('TestObjects\\', 'test/');
