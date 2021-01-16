<?php declare(strict_types = 1);

namespace App;

use AlesWita\Components\VisualPaginator;
use AlesWita\Components\VisualPaginatorFactory;
use Nette\Application\UI\Presenter;

final class TestPresenter extends Presenter
{

	/** @inject */
	public VisualPaginatorFactory $visualPaginatorFactory;

	protected function createComponentPaginator(): VisualPaginator
	{
		$paginator = $this->visualPaginatorFactory->create();

		$paginator->setItemCount(1000);

		$paginator->onPaginate[] = static function (): void {
			// some magic
		};

		return $paginator;
	}

}
