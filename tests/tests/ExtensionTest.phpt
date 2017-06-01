<?php

/**
 * This file is part of the AlesWita\Components\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita+github@gmail.com)
 *
 * @phpVersion 7.1.0
 */

declare(strict_types=1);

namespace AlesWita\Components\VisualPaginator\Tests\Tests;

use AlesWita;
use Nette;
use Tester;

require_once __DIR__ . "/../bootstrap.php";


final class ExtensionTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTest1.neon");

		$container = $configurator->createContainer();
        $container->createService("visualpaginator.visualpaginator");
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTest2.neon");

		$container = $configurator->createContainer();
        $container->createService("visualpaginator.visualpaginator");
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTest3.neon");

		$container = $configurator->createContainer();
        $container->createService("visualpaginator.visualpaginator");
	}
}


$test = new ExtensionTest;
$test->run();
