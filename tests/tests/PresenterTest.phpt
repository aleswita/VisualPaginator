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
	public function testPaginateTwo(): void {
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
		$numbers = $dom->find("select option");

		Tester\Assert::count(6, $numbers);
		Tester\Assert::same(10, (int) $numbers[0]);
		Tester\Assert::same(20, (int) $numbers[1]);
		Tester\Assert::same(30, (int) $numbers[2]);
		Tester\Assert::same(40, (int) $numbers[3]);
		Tester\Assert::same(50, (int) $numbers[4]);
		Tester\Assert::same(100, (int) $numbers[5]);
	}

	/**
	 * @return void
	 */
	public function testTemplateNormalFour(): void {
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
	}

	/**
	 * @return void
	 */
	public function atestOne(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "templateOne"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = Nette\Utils\Strings::normalize((string) $response->getSource());
		$template = Nette\Utils\Strings::normalize('<em>&laquo;</em>

 <strong><a href="/test/template-one?paginatorOne-page=1&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">1</a></strong>

 <strong><a href="/test/template-one?paginatorOne-page=2&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">2</a></strong>

 <strong><a href="/test/template-one?paginatorOne-page=3&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">3</a></strong>

 <strong><a href="/test/template-one?paginatorOne-page=4&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">4</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=251&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">251</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=501&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">501</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=750&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">750</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=1000&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">1000</a></strong>


<a href="/test/template-one?paginatorOne-page=2&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">&raquo;</a>');

		Tester\Assert::same($template, $source);
	}

	/**
	 * @return void
	 */
	public function atestTwo(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "templateTwo"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = Nette\Utils\Strings::normalize((string) $response->getSource());
		$template = Nette\Utils\Strings::normalize('<em>&laquo;</em>

 <strong><a href="/test/template-two?paginatorTwo-page=1&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">1</a></strong>

 <strong><a href="/test/template-two?paginatorTwo-page=2&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">2</a></strong>

 <strong><a href="/test/template-two?paginatorTwo-page=3&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">3</a></strong>

 <strong><a href="/test/template-two?paginatorTwo-page=4&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">4</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=251&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">251</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=501&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">501</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=750&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">750</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=1000&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">1000</a></strong>


<a href="/test/template-two?paginatorTwo-page=2&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">&raquo;</a>
 <form action="/test/template-two" method="post" id="frm-paginatorTwo-itemsPerPage">
  <table>
   <tr>
    <td><label for="frm-paginatorTwo-itemsPerPage-itemsPerPage">Items per page</label></td><td><select name="itemsPerPage" id="frm-paginatorTwo-itemsPerPage-itemsPerPage" required data-nette-rules=\'[{"op":":filled","msg":"This field is required."}]\'><option value="10" selected>10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="100">100</option></select></td><td><input type="submit" name="send" value="Send"></td>
   </tr>
  </table>
 <input type="hidden" name="_do" value="paginatorTwo-itemsPerPage-submit"><!--[if IE]><input type=IEbug disabled style="display:none"><![endif]-->
</form>');

		Tester\Assert::same($template, $source);
	}

	/**
	 * @return void
	 */
	public function atestThree(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "templateOne"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = Nette\Utils\Strings::normalize((string) $response->getSource());
		$template = Nette\Utils\Strings::normalize('<em>&laquo;</em>

 <strong><a href="/test/template-one?paginatorOne-page=1&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">1</a></strong>

 <strong><a href="/test/template-one?paginatorOne-page=2&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">2</a></strong>

 <strong><a href="/test/template-one?paginatorOne-page=3&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">3</a></strong>

 <strong><a href="/test/template-one?paginatorOne-page=4&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">4</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=251&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">251</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=501&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">501</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=750&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">750</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-one?paginatorOne-page=1000&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">1000</a></strong>


<a href="/test/template-one?paginatorOne-page=2&amp;paginatorOne-itemsPerPage=10&amp;do=paginatorOne-paginate">&raquo;</a>');

		Tester\Assert::same($template, $source);
	}

	/**
	 * @return void
	 */
	public function atestFour(): void {
		$presenter = $this->createPresenter();
		$request = new Nette\Application\Request("Test", "GET", ["action" => "templateTwo"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);

		$source = Nette\Utils\Strings::normalize((string) $response->getSource());
		$template = Nette\Utils\Strings::normalize('<em>&laquo;</em>

 <strong><a href="/test/template-two?paginatorTwo-page=1&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">1</a></strong>

 <strong><a href="/test/template-two?paginatorTwo-page=2&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">2</a></strong>

 <strong><a href="/test/template-two?paginatorTwo-page=3&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">3</a></strong>

 <strong><a href="/test/template-two?paginatorTwo-page=4&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">4</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=251&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">251</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=501&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">501</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=750&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">750</a></strong>
 <em>&hellip;</em>
 <strong><a href="/test/template-two?paginatorTwo-page=1000&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">1000</a></strong>


<a href="/test/template-two?paginatorTwo-page=2&amp;paginatorTwo-itemsPerPage=10&amp;do=paginatorTwo-paginate">&raquo;</a>
 <form action="/test/template-two" method="post" id="frm-paginatorTwo-itemsPerPage">
  <table>
   <tr>
    <td><label for="frm-paginatorTwo-itemsPerPage-itemsPerPage">Items per page</label></td><td><select name="itemsPerPage" id="frm-paginatorTwo-itemsPerPage-itemsPerPage" required data-nette-rules=\'[{"op":":filled","msg":"This field is required."}]\'><option value="10" selected>10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="100">100</option></select></td><td><input type="submit" name="send" value="Send"></td>
   </tr>
  </table>
 <input type="hidden" name="_do" value="paginatorTwo-itemsPerPage-submit"><!--[if IE]><input type=IEbug disabled style="display:none"><![endif]-->
</form>');

		Tester\Assert::same($template, $source);
	}
}


$test = new PresenterTest;
$test->run();
