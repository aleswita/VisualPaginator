<?php

/**
 * This file is part of the AlesWita\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita+github@gmail.com)
 */

namespace AlesWita\Components;

use Nette;
use Nette\Application;
use Nette\Http;
use Nette\Utils;


/**
 * @author Ales Wita
 * @author David Grudl
 * @license MIT
 */
class VisualPaginator extends Application\UI\Control
{
	/** default session section constant */
	const SESSION_SECTION = "AlesWita\\Components\\VisualPaginator";

	/** templates constants */
	const TEMPLATE_NORMAL = "template.latte";
	const TEMPLATE_BOOTSTRAP = "bootstrap.latte";
	const TEMPLATE_BOOTSTRAP_AJAX = "bootstrap-ajax.latte";

	/* ******************** */

	/** @persistent */
	public $page = 1;

	/** @persistent */
	public $itemsPerPage = 10;

	/* ******************** */

	/** @var session */
	private $session = NULL;

	/** @var sessionSection */
	private $sessionSection = NULL;

	/** @var canSetItemsPerPage */
	private $canSetItemsPerPage = FALSE;

	/** @var itemsPerPageList */
	private $itemsPerPageList = [10 => "10", 20 => "20", 30 => "30", 40 => "40", 50 => "50", 100 => "100"];

	/** @var paginator */
	private $paginator = NULL;

	/** @var paginatorTemplate */
	private $paginatorTemplate = NULL;

	/** @var texts */
	private $texts = [
		"send" => "Send",
		"itemsPerPage" => "Items per page"
	];

	/** @var snippets */
	private $snippets = [];

	/* ******************** */

	/**
	 * @param string
	 */
	public function __construct($template = self::TEMPLATE_NORMAL)
	{
		$this->setPaginatorTemplate(__DIR__ . "/$template");
	}

	/* ******************** */

	/* ********** getters - start ********** */

	/**
	 * @return int
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @return int
	 */
	public function getItemsPerPage()
	{
		if ($this->session !== NULL) {
			$foo = $this->getSessionName();

			if (isset($this->sessionSection->$foo) && in_array($this->sessionSection->$foo, array_keys($this->getItemsPerPageList()))) {
				$this->setItemsPerPage($this->sessionSection->$foo);
				return $this->itemsPerPage;
			} else {
				unset($this->sessionSection->$foo);
				return $this->itemsPerPage;
			}
		} else {
			return $this->itemsPerPage;
		}
	}

	/**
	 * @return Nette\Http\Session
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * @return Nette\Http\SessionSection
	 */
	public function getSessionSection()
	{
		return $this->sessionSection;
	}

	/**
	 * @return bool
	 */
	public function getCanSetItemsPerPage()
	{
		return $this->canSetItemsPerPage;
	}

	/**
	 * @return array
	 */
	public function getItemsPerPageList()
	{
		return $this->itemsPerPageList;
	}

	/**
	 * @return Nette\Utils\Paginator
	 */
	public function getPaginator()
	{
		if (!$this->paginator) {
			$this->paginator = new Utils\Paginator;
		}
		$this->paginator->setPage($this->getPage());
		$this->paginator->setItemsPerPage($this->getItemsPerPage());
		return $this->paginator;
	}

	/**
	 * @return string
	 */
	public function getPaginatorTemplate()
	{
		return $this->paginatorTemplate;
	}

	/**
	 * @return array
	 */
	public function getTexts()
	{
		return $this->texts;
	}

	/**
	 * @return array
	 */
	public function getSnippets()
	{
		return $this->snippets;
	}

	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->getPaginator()->offset;
	}

	/**
	 * @return string
	 */
	public function getSessionName()
	{
		$presenterName = $this->presenter->getRequest()->getPresenterName();
		$presenterParameters = $this->presenter->getRequest()->getParameters();
		$match = Utils\Strings::match($presenterName, "~^(([a-zA-Z0-9]+):([a-zA-Z0-9]+))|([a-zA-Z0-9]+)$~");

		if (isset($match[2]) && isset($match[3]) && $match[2] !== NULL && $match[3] !== NULL && $match[2] !== "" && $match[3] !== "") {
			return $match[2] . "-" . $match[3] . "-" . $presenterParameters["action"];
		} elseif (isset($match[4]) && $match[4] !== NULL && $match[4] !== "") {
			return $match[4] . "-" . $presenterParameters["action"];
		} else {
			return "default";
		}
	}

	/**
	 * @return string
	 */
	public function getDir()
	{
		return __DIR__;
	}

	/* ********** getters - end ********** */

	/* ********** setters - start ********** */

	/**
	 * @param int
	 * @return self
	 */
	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}

	/**
	 * @param int
	 * @return self
	 */
	public function setItemsPerPage($num)
	{
		if ($this->session !== NULL) {
			$foo = $this->getSessionName();
			$this->sessionSection->$foo = (int) $num;
		}
		$this->itemsPerPage = (int) $num;
		return $this;
	}

	/**
	 * @param Nette\Http\Session
	 * @param string
	 * @return self
	 */
	public function setSession(Http\Session $session, $section = self::SESSION_SECTION)
	{
		$this->session = $session;
		$this->sessionSection = $session->getSection($section);
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function canSetItemsPerPage(array $list = NULL)
	{
		if ($list !== NULL) {
			$this->setItemsPerPageList($list);
		}
		$this->canSetItemsPerPage = TRUE;
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function setItemsPerPageList(array $list)
	{
		$this->itemsPerPageList = $list;
		return $this;
	}

	/**
	 * @param string
	 * @return self
	 */
	public function setPaginatorTemplate($template)
	{
		$this->paginatorTemplate = $template;
		return $this;
	}

	/**
	 * @param string
	 * @param string
	 * @return self
	 */
	public function setText($key, $text)
	{
		$this->texts[$key] = $text;
		return $this;
	}

	/**
	 * @param string
	 * @return self
	 */
	public function setSnippet($snippet)
	{
		if (is_array($snippet)) {
			$this->setSnippets($snippet);
		} else {
			$this->snippets[] = $snippet;
		}
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function setSnippets(array $snippets)
	{
		$this->snippets = array_merge($this->snippets, $snippets);
		return $this;
	}

	/**
	 * @param int
	 * @return self
	 */
	public function setItemCount($count)
	{
		$this->getPaginator()->itemCount = $count;
		return $this;
	}

	/* ********** setters - end ********** */

	/**
	 * @param array
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);
		$this->getPaginator()->page = $this->getPage();
		$this->setPage($this->getPaginator()->page);
		$this->getPaginator()->itemsPerPage = $this->getItemsPerPage();
	}

	public function render()
	{
		if ($this->getCanSetItemsPerPage() && !in_array($this->getItemsPerPage(), array_keys($this->getItemsPerPageList()))) {
			throw new \Exception("Items per page list haven't value '" . $this->getItemsPerPage() . "', which you set in 'setItemsPerPage()' methot.");
		}

		$paginator = $this->getPaginator();

		if ($this->getCanSetItemsPerPage()) {
			$this["itemsPerPage"]->setDefaults(["itemsPerPage" => $paginator->itemsPerPage]);
		}

		if ($paginator->pageCount < 2) {
			$foo = array($paginator->page);
		} else {
			$foo = range(max($paginator->firstPage, $paginator->page - 3), min($paginator->lastPage, $paginator->page + 3));
			$count = 4;
			$quotient = ($paginator->pageCount - 1) / $count;

			for ($i = 0; $i <= $count; $i++) {
				$foo[] = round($quotient * $i) + $paginator->firstPage;
			}
			sort($foo);
		}

		$this->template->paginator = $paginator;
		$this->template->steps = array_values(array_unique($foo));
		$this->template->itemsPerPage = $this->getCanSetItemsPerPage();
		$this->template->texts = $this->getTexts();

		$this->template->setFile($this->getPaginatorTemplate());
		$this->template->render();
	}

	/* ******************** */

	public function handlePaginate()
	{
		if ($this->presenter->isAjax() && count($this->getSnippets()) > 0) {
			foreach ($this->getSnippets() as $snippet) {
				$this->parent->redrawControl($snippet);
			}
			$this->redrawControl("paginator");
		}
	}

	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentItemsPerPage()
	{
		$form = new Application\UI\Form;

		$form->addSelect("itemsPerPage", NULL, $this->getItemsPerPageList())
			//->setAttribute("onchange", "this.form.submit()")
			->setRequired();

		$form->addSubmit("set");

		$form->onSuccess[] = [$this, "itemsPerPageSuccess"];

		return $form;
	}

	/**
	 * @param Nette\Application\UI\Form
	 * @param array
	 */
	public function itemsPerPageSuccess(Application\UI\Form $form, $values)
	{
		$this->setItemsPerPage($values->itemsPerPage);
		$this->handlePaginate();
	}
}
