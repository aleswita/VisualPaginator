<?php

/**
 * This file is part of the AlesWita\Components\VisualPaginator
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
	const SESSION_SECTION = "Visual-Paginator";

	/** templates constants */
	const TEMPLATE_NORMAL = [
		"main" => __DIR__ . "/templates/normal/main.latte",
		"paginator" => __DIR__ . "/templates/normal/paginator.latte",
		"itemsPerPage" => __DIR__ . "/templates/normal/items-per-page.latte"
	];
	const TEMPLATE_BOOTSTRAP_V3 = [
		"main" => __DIR__ . "/templates/bootstrap-v3/main.latte",
		"paginator" => __DIR__ . "/templates/bootstrap-v3/paginator.latte",
		"itemsPerPage" => __DIR__ . "/templates/bootstrap-v3/items-per-page.latte"
	];

	/** ******************** */

	/** @persistent */
	public $page = 1;

	/** @persistent */
	public $itemsPerPage = 10;

	/** ******************** */

	/** @var session */
	private $session = NULL;

	/** @var sessionSection */
	private $sessionSection = NULL;

	/** @var canSetItemsPerPage */
	private $canSetItemsPerPage = FALSE;

	/** @var itemsPerPageList */
	private $itemsPerPageList = [10 => "10", 20 => "20", 30 => "30", 40 => "40", 50 => "50", 100 => "100"];

	/** @var itemsPerPageReposity */
	private $itemsPerPageReposity = NULL;

	/** @var paginator */
	private $paginator = NULL;

	/** @var paginatorTemplate */
	private $paginatorTemplate = NULL;

	/** @var texts */
	private $texts = [
		"send" => "Send",
		"itemsPerPage" => "Items per page"
	];

	/** @var ajax */
	private $ajax = FALSE;

	/** @var snippets */
	private $snippets = [];

	/** ******************** */

	/**
	 * @param array
	 */
	public function __construct($template = self::TEMPLATE_NORMAL)
	{
		$this->setPaginatorTemplate($template);
	}

	/** ******************** */

	/** ********** getters - start ********** */

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
		if ($this->session !== NULL && $this->canSetItemsPerPage) {
			$foo = $this->getSessionReposity();

			if (isset($this->sessionSection->$foo) && in_array($this->sessionSection->$foo, array_keys($this->itemsPerPageList), TRUE)) {
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
	private function getSession()
	{
		return $this->session;
	}

	/**
	 * @return Nette\Http\SessionSection
	 */
	private function getSessionSection()
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
	 * @return array
	 */
	public function getItemsPerPageReposity()
	{
		return $this->itemsPerPageReposity;
	}

	/**
	 * @return Nette\Utils\Paginator
	 */
	public function getPaginator()
	{
		if ($this->paginator === NULL) {
			$this->paginator = new Utils\Paginator;
		}
		$this->paginator->page = $this->page;
		$this->paginator->itemsPerPage = $this->getItemsPerPage();
		return $this->paginator;
	}

	/**
	 * @return array
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
	 * @return bool
	 */
	public function getAjax()
	{
		return $this->ajax;
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
	public function getDir()
	{
		return __DIR__;
	}

	/**
	 * @return string
	 */
	private function getSessionReposity()
	{
		if ($this->itemsPerPageReposity !== NULL) {
			return $this->itemsPerPageReposity;
		} else {
			$presenterName = $this->presenter->getRequest()->getPresenterName();
			$presenterParameters = $this->presenter->getRequest()->getParameters();
			$match = Utils\Strings::match($presenterName, "~^(([a-zA-Z0-9_]+):([a-zA-Z0-9_]+))|([a-zA-Z0-9_]+)$~");

			if (isset($match[2]) && isset($match[3]) && $match[2] !== "" && $match[3] !== "") {// module:presenter
				return Utils\Strings::lower($match[2] . "-" . $match[3] . "-" . $presenterParameters["action"]);
			} elseif (isset($match[4]) && $match[4] !== "") {// presenter
				return Utils\Strings::lower($match[4] . "-" . $presenterParameters["action"]);
			} else {
				return "default";
			}
		}
	}

	/** ********** getters - end ********** */

	/** ********** setters - start ********** */

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
	 * @throw Nette\InvalidArgumentException
	 */
	public function setItemsPerPage($num)
	{
		if ($this->canSetItemsPerPage && !in_array($num, array_keys($this->itemsPerPageList), TRUE)) {
			throw new Nette\InvalidArgumentException("\$itemsPerPageList array haven't value '" . $num . ".");
		}
		if ($this->session !== NULL) {
			$foo = $this->getSessionReposity();
			$this->sessionSection->$foo = (int) $num;
		}
		$this->itemsPerPage = (int) $num;
		return $this;
	}

	/**
	 * @param string
	 * @return self
	 */
	public function setItemsPerPageReposity($itemsPerPageReposity)
	{
		if ($itemsPerPageReposity !== NULL) {
			$this->itemsPerPageReposity = Utils\Strings::lower($itemsPerPageReposity);
		}
		return $this;
	}

	/**
	 * @param Nette\Http\Session
	 * @param string
	 * @param string
	 * @return self
	 */
	public function setSession(Http\Session $session, $itemsPerPageReposity = NULL, $section = self::SESSION_SECTION)
	{
		$this->session = $session;
		$this->setItemsPerPageReposity($itemsPerPageReposity);
		$this->sessionSection = $session->getSection($section);
		return $this;
	}

	/**
	 * @param array
	 * @param bool
	 * @return self
	 */
	public function canSetItemsPerPage(array $list = NULL, $merge = FALSE)
	{
		if ($list !== NULL) {
			$this->setItemsPerPageList($list, $merge);
		}
		$this->canSetItemsPerPage = TRUE;
		return $this;
	}

	/**
	 * @param array
	 * @param bool
	 * @return self
	 * @throw Nette\InvalidArgumentException
	 */
	public function setItemsPerPageList(array $list, $merge = FALSE)
	{
		if ($list !== array_filter($list, function ($s) {return is_numeric($s);})) {
			throw new Nette\InvalidArgumentException("Keys in \$itemsPerPageList array must be numeric.");
		}

		if ($merge) {
			$this->itemsPerPageList = array_merge($this->itemsPerPageList, $list);
			ksort($this->itemsPerPageList);
		} else {
			$this->itemsPerPageList = $list;
		}
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 * @throw Nette\InvalidArgumentException
	 */
	public function setPaginatorTemplate(array $template)
	{
		if (array_keys($template) !== array_keys(self::TEMPLATE_NORMAL)) {
			throw new Nette\InvalidArgumentException("Template array must have these keys: main, paginator and itemsPerPage.");
		}
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
	 * @param bool
	 * @return self
	 */
	public function setAjax($ajax = TRUE)
	{
		$this->ajax = $ajax;
		return $this;
	}

	/**
	 * @param array
	 * @param bool
	 * @return self
	 */
	public function setSnippets(array $snippets, $merge = TRUE)
	{
		if (!$merge) {
			$this->snippets = [];
		}
		foreach($snippets as $snippet) {
			$this->setSnippet($snippet, $merge);
		}
		return $this;
	}

	/**
	 * @param string
	 * @param bool
	 * @return self
	 */
	public function setSnippet($snippet)
	{
		if(!$this->getAjax()) {
			$this->setAjax();
		}
		if(!in_array($snippet, $this->snippets, TRUE)) {
			$this->snippets[] = $snippet;
		}
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

	/** ********** setters - end ********** */

	/**
	 * @param array
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);
		$this->getPaginator()->page = $this->getPage();
		$this->page = $this->getPaginator()->page;
		$this->getPaginator()->itemsPerPage = $this->getItemsPerPage();
	}

	public function render()
	{
		$paginator = $this->getPaginator();

		if ($this->canSetItemsPerPage) {
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

		$this->template->steps = array_values(array_unique($foo));
		$this->template->itemsPerPage = $this->canSetItemsPerPage;
		$this->template->paginator = $paginator;
		$this->template->texts = $this->texts;
		$this->template->ajax = $this->ajax;

		$this->template->setFile($this->paginatorTemplate["main"]);
		$this->template->render();
	}

	public function renderPaginator()
	{
		$paginator = $this->getPaginator();

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

		$this->template->steps = array_values(array_unique($foo));
		$this->template->paginator = $paginator;
		$this->template->ajax = $this->ajax;

		$this->template->setFile($this->paginatorTemplate["paginator"]);
		$this->template->render();
	}

	/**
	 * @throw Nette\InvalidArgumentException
	 */
	public function renderItemsPerPage()
	{
		if ($this->canSetItemsPerPage) {
			$this["itemsPerPage"]->setDefaults(["itemsPerPage" => $this->getPaginator()->itemsPerPage]);
		}

		$this->template->itemsPerPage = $this->canSetItemsPerPage;
		$this->template->texts = $this->texts;
		$this->template->ajax = $this->ajax;

		$this->template->setFile($this->paginatorTemplate["itemsPerPage"]);
		$this->template->render();
	}

	/** ******************** */

	public function handlePaginate()
	{
		if ($this->presenter->isAjax() && count($this->getSnippets()) > 0) {
			foreach ($this->getSnippets() as $snippet) {
				$this->parent->redrawControl($snippet);
			}
			$this->redrawControl("paginator");
			$this->redrawControl("itemsPerPage");
		}
	}

	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentItemsPerPage()
	{
		$form = new Application\UI\Form;

		$form->addSelect("itemsPerPage", NULL, $this->itemsPerPageList)
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
