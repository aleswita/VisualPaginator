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
use Tester;

require_once __DIR__ . "/../bootstrap.php";


final class BasicTest extends Tester\TestCase
{
	/** @var AlesWita\Components\VisualPaginator */
	private $vp;

	public function __construct()
	{
		$this->vp = new AlesWita\Components\VisualPaginator;
	}

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->vp->setItemsPerPage(10)
			->setItemCount(10000);

		$this->vp->page = 1;
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testOne(): void {
		$paginator = $this->vp->getPaginator();

		Tester\Assert::type("AlesWita\\Components\\VisualPaginator", $this->vp);

		// public variables
		Tester\Assert::same(1, $this->vp->page);
		Tester\Assert::same(10, $this->vp->itemsPerPage);
		Tester\Assert::same(NULL, $this->vp->onPaginate);

		// public static variables
		Tester\Assert::same([10 => "10", 20 => "20", 30 => "30", 40 => "40", 50 => "50", 100 => "100"], $this->vp::$itemsPerPageList);
		Tester\Assert::same($this->vp::TEMPLATE_NORMAL, $this->vp::$paginatorTemplate);
		Tester\Assert::same(["send" => "Send", "itemsPerPage" => "Items per page"], $this->vp::$messages);

		// public getters
		Tester\Assert::type("Nette\\Utils\\Paginator", $paginator);
		Tester\Assert::same(0, $this->vp->getOffset());
		Tester\Assert::same(10, $this->vp->getItemsPerPage());

		// paginator instance
		Tester\Assert::same(1, $paginator->getPage());
		Tester\Assert::same(1, $paginator->getFirstPage());
		Tester\Assert::same(1000, $paginator->getLastPage());
		Tester\Assert::same(1, $paginator->getBase());
		Tester\Assert::same(TRUE, $paginator->isFirst());
		Tester\Assert::same(FALSE, $paginator->isLast());
		Tester\Assert::same(1000, $paginator->getPageCount());
		Tester\Assert::same(10, $paginator->getItemsPerPage());
		Tester\Assert::same(10000, $paginator->getItemCount());
		Tester\Assert::same(0, $paginator->getOffset());
		Tester\Assert::same(9990, $paginator->getCountdownOffset());
		Tester\Assert::same(10, $paginator->getLength());
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		Tester\Assert::noError(function (): void {$this->vp->setItemsPerPage(3);});
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 * @return void
	 */
	public function testThree(): void {
		$this->vp->setCanSetItemsPerPage(TRUE);
		$this->vp->setItemsPerPage(3);
	}

	/**
	 * @return void
	 */
	public function testFour(): void {
		$this->vp->itemsPerPage = 50;
		Tester\Assert::same(50, $this->vp->getItemsPerPage());
		Tester\Assert::same(50, $this->vp->getPaginator()->getItemsPerPage());
	}

	/**
	 * @return void
	 */
	public function testFive(): void {
		$this->vp->setItemsPerPage(100);
		Tester\Assert::same(100, $this->vp->getItemsPerPage());
		Tester\Assert::same(100, $this->vp->getPaginator()->getItemsPerPage());
	}

	/**
	 * @return void
	 */
	public function testSix(): void {
		$this->vp->page = 20;
		Tester\Assert::same(20, $this->vp->getPaginator()->getPage());
	}

	/**
	 * @return void
	 */
	public function testSeven(): void {
		$this->vp->page = 20;
		$this->vp->setItemsPerPage(100);
		Tester\Assert::same(1900, $this->vp->getOffset());
	}

	/**
	 * @return void
	 */
	public function testEight(): void {
		$this->vp->page = 1001;
		Tester\Assert::same(1000, $this->vp->getPaginator()->getPage());
	}

	/**
	 * @return void
	 */
	public function testNine(): void {
		$this->vp->page = 1001;
		Tester\Assert::same(9990, $this->vp->getOffset());
	}
}

$test = new BasicTest;
$test->run();
