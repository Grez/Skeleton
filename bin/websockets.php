<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';

$rootDir = __DIR__ . '/..';

$params = [
	'wwwDir' => $rootDir . '/www',
	'appDir' => $rootDir . '/app',
	'tempDir' => $rootDir . '/temp',
	'testMode' => TRUE
];
$configurator = (new Teddy\Configurator($params))
//	->addConfig(__DIR__ . '/config.neon')
	->setDebugMode(TRUE);
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->addDirectory(__DIR__ . '/../vendor/teddy/framework/app') // why doesn't autoloader fetch that... check PSR autoloading :/
	->register();
$container = $configurator->createContainer();

$server = IoServer::factory(
	new HttpServer(
		new WsServer(
			new \Game\WebSockets\Controller($container)
		)
	),
	8080
);

$server->run();
