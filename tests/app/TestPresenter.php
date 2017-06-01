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

	/** @var Nette\Http\Session @inject */
	public $session;

	/**
	 * @return void
	 */
	public function actionFormOne(): void {
		$this["paginator"]->setCanSetItemsPerPage(TRUE)
			->setItemCount(50);

		$this->setView("template");
	}

	/**
	 * @return void
	 */
	public function actionFormTwo(): void {
		$this["paginator"]->setCanSetItemsPerPage(TRUE)
			->setItemCount(50);

		$this->setView("template");
	}

	/**
	 * @return void
	 */
	public function actionSessionOne(): void {
		$this->setView("template");
	}

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
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginator(): AlesWita\Components\VisualPaginator {
		return $this->visualPaginator;
	}
}
