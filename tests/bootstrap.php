<?php

declare(strict_types=1);

define('ROOT_DIR', __DIR__ . '/../..');
define('APP_DIR', ROOT_DIR . '/app');

require ROOT_DIR . '/vendor/autoload.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator;

//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
$configurator->setDebugMode(true);
$configurator->enableTracy(ROOT_DIR . '/log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(ROOT_DIR . '/temp');

$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(ROOT_DIR . '/src')
	->register();

$configurator->addConfig(APP_DIR . '/config/config.neon');
$configurator->addConfig(APP_DIR . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
