<?php

/**
 * This file is part of the AlesWita\Components\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\VisualPaginator\Tests\App;

use AlesWita;
use Nette;


final class TestPresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\Components\VisualPaginator @inject */
	public $visualPaginator;

	/**
	 * @return void
	 */
	public function actionTemplateOne(): void {
		$this->setView("one");
	}

	/**
	 * @return void
	 */
	public function actionTemplateTwo(): void {
		$this->setView("two");
	}

	/**
	 * @return void
	 */
	public function actionTemplateThree(): void {
		$this["paginatorOne"]::$paginatorTemplate = TEMPLATE_BOOTSTRAP_V4;
		$this->setView("one");
	}

	/**
	 * @return void
	 */
	public function actionTemplateFour(): void {
		$this["paginatorTwo"]::$paginatorTemplate = TEMPLATE_BOOTSTRAP_V4;
		$this->setView("two");
	}

	/**
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginatorOne(): AlesWita\Components\VisualPaginator {
		$vp = $this->visualPaginator;

		$vp->setItemsPerPage(10)
			->setItemCount(10000);

		return $vp;
	}

	/**
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginatorTwo(): AlesWita\Components\VisualPaginator {
		$vp = $this->visualPaginator;

		$vp->setCanSetItemsPerPage(TRUE)
			->setItemsPerPage(10)
			->setItemCount(10000);

		return $vp;
	}
}
