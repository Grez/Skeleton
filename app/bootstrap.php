<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = (new Teddy\Configurator);

$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->addDirectory(__DIR__ . '/../vendor/teddy/map/src') // why doesn't autoloader fetch that... check PSR autoloading :/
    ->addDirectory(__DIR__ . '/../vendor/teddy/framework/app') // why doesn't autoloader fetch that... check PSR autoloading :/
    ->register();

$container = $configurator->createContainer();

return $container;
