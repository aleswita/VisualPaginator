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
	public function actionNormalTemplateOne(): void {
		$this["paginator"]->setItemCount(50);
		$this->setView("template");
	}

	/**
	 * @return void
	 */
	public function actionNormalTemplateTwo(): void {
		$this["paginator"]->setItemCount(10);
		$this->setView("template");
	}

	/**
	 * @return void
	 */
	public function actionNormalTemplateThree(): void {
		$this["paginator"]->setItemCount(10)
			->setCanSetItemsPerPage(TRUE);

		$this->setView("template");
	}

	/**
	 * @return void
	 */
	public function actionNormalTemplateFour(): void {
		AlesWita\Components\VisualPaginator::$itemsPerPageList = [2 => 2, 4 => 4, 6 => 6];

		$this["paginator"]->setItemCount(10)
			->setCanSetItemsPerPage(TRUE);

		$this->setView("template");
	}

	/**
	 * @return void
	 */
	public function actionNormalTemplateFive(): void {
		$this["paginator"]->itemsPerPage = 5;

		$this["paginator"]->setItemCount(10)
			->setCanSetItemsPerPage(TRUE);

		$this->setView("template");
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
	protected function createComponentPaginator(): AlesWita\Components\VisualPaginator {
		return $this->visualPaginator;
	}
}
