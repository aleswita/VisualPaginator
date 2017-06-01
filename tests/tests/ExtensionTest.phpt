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
	/** @var \SystemContainer|\Nette\DI\Container */
	private $container;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		//$this->container = $this->createContainer();
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

		$configurator->setDebugMode(TRUE);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTest1.neon");

		$

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();

		$configurator->setDebugMode(TRUE);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTest1.neon");

		$configurator->createContainer();
	}configurator->createContainer();
	}
}


$test = new ExtensionTest;
$test->run();
