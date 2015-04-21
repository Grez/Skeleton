<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Teddy\Configurator;
//$configurator->setDebugMode(false);

$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

$container = $configurator->createContainer();

return $container;
