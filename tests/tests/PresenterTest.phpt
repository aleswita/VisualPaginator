<?php

/**
 * This file is part of the AlesWita\Components\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita+github@gmail.com)
 *
 * @phpVersion 7.1.0
 */

declare(strict_types=1);

namespace AlesWita\Components\VisualPaginator\Tests;

use AlesWita;
use Nette;
use Tester;

require_once __DIR__ . "/../bootstrap.php";
require_once __DIR__ . "/../app/TestPresenter.php";
require_once __DIR__ . "/../app/Router.php";


final class PresenterTest extends Tester\TestCase
{
	/** @var Nette\Application\IPresenterFactory */
	private $presenterFactory;

	/** @var \SystemContainer|\Nette\DI\Container */
	private $container;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->container = $this->createContainer();
		$this->presenterFactory = $this->container->getByType("Nette\\Application\\IPresenterFactory");
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @return Nette\DI\Container
	 */
	private function createContainer(): Nette\DI\Container {
		$configurator = new Nette\Configurator();

		$configurator->setDebugMode(TRUE);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");

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
	 * @return void
	 */
	public function testFormOne(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "POST", ["action" => "formOne"], ["itemsPerPage" => "20", "_do" => "paginator-itemsPerPage-submit", "send" => "Send"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\RedirectResponse);
		Tester\Assert::contains("form-one?paginator-page=1&paginator-itemsPerPage=20", $response->getUrl());
		Tester\Assert::true($presenter["paginator"]["itemsPerPage"]->isSuccess());
	}

	/**
	 * @return void
	 */
	public function testFormTwo(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "formTwo", "do" => "paginator-paginate", "paginator-page" => 2, "paginator-itemsPerPage" => 20]);
		$session = $presenter->getSession();
		$sessionSection = $session->getSection(AlesWita\Components\VisualPaginator::SESSION_SECTION);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$option = $dom->find("select option[selected]");

		Tester\Assert::count(1, $option);
		Tester\Assert::same(20, (int) $option[0]["value"]);
	}

	/**
	 * @return void
	 */
	public function testSessionOne(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "sessionOne"]);
		$session = $presenter->getSession();
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($session->hasSection(AlesWita\Components\VisualPaginator::SESSION_SECTION));
	}

	/**
	 * @return void
	 */
	public function testPaginateOne(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "paginateOne", "do" => "paginator-paginate", "paginator-page" => 2, "paginator-itemsPerPage" => 10]);
		$session = $presenter->getSession();
		$sessionSection = $session->getSection(AlesWita\Components\VisualPaginator::SESSION_SECTION);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\RedirectResponse);
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalOne(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "normalTemplateOne"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$numbers = $dom->find("strong a");

		Tester\Assert::count(5, $numbers);
		Tester\Assert::same(1, (int) $numbers[0]);
		Tester\Assert::same(2, (int) $numbers[1]);
		Tester\Assert::same(3, (int) $numbers[2]);
		Tester\Assert::same(4, (int) $numbers[3]);
		Tester\Assert::same(5, (int) $numbers[4]);
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalTwo(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "normalTemplateTwo"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$numbers = $dom->find("strong a");

		Tester\Assert::count(1, $numbers);
		Tester\Assert::same(1, (int) $numbers[0]);
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalThree(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "normalTemplateThree"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$options = $dom->find("select option");

		Tester\Assert::count(6, $options);
		Tester\Assert::same(10, (int) $options[0]);
		Tester\Assert::same(20, (int) $options[1]);
		Tester\Assert::same(30, (int) $options[2]);
		Tester\Assert::same(40, (int) $options[3]);
		Tester\Assert::same(50, (int) $options[4]);
		Tester\Assert::same(100, (int) $options[5]);
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalFour(): void {
		$temp = AlesWita\Components\VisualPaginator::$itemsPerPageList;
        AlesWita\Components\VisualPaginator::$itemsPerPageList = [2 => 2, 4 => 4, 6 => 6];

		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "normalTemplateFour"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$numbers = $dom->find("select option");

		Tester\Assert::count(3, $numbers);
		Tester\Assert::same(2, (int) $numbers[0]);
		Tester\Assert::same(4, (int) $numbers[1]);
		Tester\Assert::same(6, (int) $numbers[2]);

		AlesWita\Components\VisualPaginator::$itemsPerPageList = $temp;
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalFive(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "normalTemplateFive"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$numbers = $dom->find("strong a");

		Tester\Assert::count(5, $numbers);
		Tester\Assert::same(1, (int) $numbers[0]);
		Tester\Assert::same(2, (int) $numbers[1]);
		Tester\Assert::same(3, (int) $numbers[2]);
		Tester\Assert::same(4, (int) $numbers[3]);
		Tester\Assert::same(5, (int) $numbers[4]);
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalSix(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "normalTemplateSix"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$options = $dom->find("select option");

		Tester\Assert::count(6, $options);
		Tester\Assert::same(10, (int) $options[0]);
		Tester\Assert::same(20, (int) $options[1]);
		Tester\Assert::same(30, (int) $options[2]);
		Tester\Assert::same(40, (int) $options[3]);
		Tester\Assert::same(50, (int) $options[4]);
		Tester\Assert::same(100, (int) $options[5]);
	}
}


$test = new PresenterTest;
$test->run();
