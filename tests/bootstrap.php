<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator();

$configurator->setTempDirectory(__DIR__ . '/../temp')
	->addConfig(__DIR__ . '/App/config/config.neon');;

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $configurator->createContainer();
