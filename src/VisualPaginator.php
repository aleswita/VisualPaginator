<?php

/**
 * This file is part of the HandOverUnitsModule
 * Copyright (c) 2016 Ales Wita (aleswita@gmail.com)
 */

namespace App\HandOverUnitsModule\Components;

use App;
use AlesWita;
use Doctrine;
use Nette;
use Nette\Application;
use Nette\Forms;
use Nette\Utils;


/**
 * @author Aleš Wita
 */
final class Table extends Application\UI\Control
{
	/** @persistent */
	public $filter = [];

	/** @var App\HandOverUnitsModule\Model\HandOverUnits */
	private $model;

	/** @var Nette\Localization\ITranslator */
	private $translator;

	/**
	 * @param App\HandOverUnitsModule\Model\HandOverUnits
	 */
	public function __construct(App\HandOverUnitsModule\Model\HandOverUnits $model)
	{
		$this->model = $model;
	}

	/**
	 * @param Nette\Localization\ITranslator
	 * @return self
	 */
	public function setTranslator(Nette\Localization\ITranslator $translator): self {
		$this->translator = $translator;
		return $this;
	}

	/** ******************** */

	public function render()
	{
		$qb = $this->model->getEm()->createQueryBuilder();

		$qb->select("i")
			->from("App\HandOverUnitsModule\Model\Entity\Trays", "t")
			->leftJoin("App\HandOverUnitsModule\Model\Entity\TraysItems", "i", Doctrine\ORM\Query\Expr\Join::WITH, "t.trayId = i.trayId")
			->orderBy("i.trayId", "DESC");

		// namespace
		$namespaces = $this->model->getNamespaces();
		$qb->andWhere("t.namespace IN (:ns)")
			->setParameter("ns", array_keys($namespaces));

		$this["paginator"]->setItemCount(count($qb->getQuery()->getResult()));

		$qb->setFirstResult($this["paginator"]->getOffset())
			->setMaxResults($this["paginator"]->getItemsPerPage());

		$this->template->traysItems = $qb->getQuery()->getResult();

		$this->template->setFile(__DIR__ . "/table.latte");
		$this->template->render();
	}

	/**
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginator(): AlesWita\Components\VisualPaginator {
		$vp = new AlesWita\Components\VisualPaginator;

		$vp->setSession($this->model->getSession())
			->setTranslator($this->translator)
			->setPaginatorTemplate(AlesWita\Components\VisualPaginator::TEMPLATE_BOOTSTRAP_V3)
			->setCanSetItemsPerpage(TRUE)
			->setAjax(TRUE)
			->setText("send", "system.paginator.send")
			->setText("itemsPerPage", "system.paginator.itemsPerPage");

		$vp->onPaginate[] = function(){
			if ($this->presenter->isAjax()) {
				$this->presenter->redrawControl("flashMessage");
				$this->redrawControl("table");
			}
		};

		return $vp;
	}

	/**
	 * @return Nette\Application\UI\Multiplier
	 */
	protected function createComponentTrayItem(): Nette\Application\UI\Multiplier {
		return new Application\UI\Multiplier(function (int $id) {
			$ticket = new TrayItem($id, $this->model->getTraysItems()->findOneBy(["itemId" => $id]), $this->model);
			$ticket->setTranslator($this->translator);
			return $ticket;
		});
	}
}
