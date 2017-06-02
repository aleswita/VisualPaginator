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


final class ExtTest extends Tester\TestCase
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
	 * @param string
	 * @return Nette\DI\Container
	 */
	private function createContainer(string $config): Nette\DI\Container {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig($config);

		return $configurator->createContainer();
	}

	/**
	 * @return Nette\Application\IPresenter
	 */
	private function createPresenter(): Nette\Application\IPresenter {
		$presenter = $this->presenterFactory->createPresenter("Test");
		$presenter->autoCanonicalize = FALSE;
		return $presenter;
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extTest1.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extTest2.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extTest3.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testFour(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extTest4.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testFive(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extTest5.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testSix(): void {
		$configurator = new Nette\Configurator();

		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/extTest6.neon");

		$configurator->createContainer();
	}
}


$test = new ExtTest;
$test->run();
