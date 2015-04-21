<?php

// Maintenance lockdown
if (file_exists(__DIR__ . '/maintenance.php')) {
    require __DIR__ . '/maintenance.php';
    exit;
}

$container = require __DIR__ . '/../app/bootstrap.php';

$container->getByType('Nette\Application\Application')->run();
