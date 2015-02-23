<?php

namespace AlesWita\Components;

use Nette\Utils\Paginator,
    Nette\Application\UI\Control,
    Nette\Application\UI\Form;

/**
  * @author Ales Wita
  * @author David Grudl
  * @license MIT
  */
class VisualPaginator extends Control
{
  /** @persistent */
  public $page = 1;

  /** @persistent */
  public $items_per_page = 10;

  /* ******************** */

  /** @var paginator */
  private $paginator;

  /** @var paginatorTemplate */
  private $paginatorTemplate;

  /** @var canSetItemsPerPage */
  private $canSetItemsPerPage = false;

  /** @var itemsPerPageList */
  private $itemsPerPageList = [10 => "10",20 => "20",30 => "30",40 => "40",50 => "50",100 => "100"];

  /** @var translator */
  private $translator;

  /**
    * __construct
    */
  public function __construct()
  {
    $this->paginator = new Paginator;
    $this->paginatorTemplate = __DIR__."/template.latte";
  }

	/**
	  * getPaginator
    * @return Nette\Utils\Paginator
    */
  public function getPaginator()
  {
    $this->paginator->setPage($this->page);
    $this->paginator->setItemsPerPage($this->items_per_page);
    return $this->paginator;
  }

  /**
    * setItemCount
    * @param int
    */
  public function setItemCount($data)
  {
    $this->paginator->setItemCount($data);
  }

  /**
    * setItemsPerPage
    * @param int
    */
  public function setItemsPerPage($data)
  {
    $this->paginator->setItemsPerPage($data);
  }

  /**
    * setDefaultPage
    * @param int
    */
  public function setDefaultPage($data)
  {
    $this->page = $data;
  }

  /**
    * canSetItemsPerPage
    * @param bool or array
    */
  public function canSetItemsPerPage($data=null)
  {
    if(is_Array($data))
      $this->itemsPerPageList = $data;

    $this->canSetItemsPerPage = true;
  }

  /**
    * setPaginatorTemplate
    * @param string
    */
  public function setPaginatorTemplate($data)
  {
    $this->paginatorTemplate = $data;
  }

  /**
    * getItemsPerPage
    * @return int
    */
  public function getItemsPerPage()
  {
    return $this->paginator->getItemsPerPage();
  }

  /**
    * getOffset
    * @return int
    */
  public function getOffset()
  {
    return $this->paginator->getOffset();
  }

  /**
    * setTranslator
    * @param translator
    */
  public function setTranslator(\Kdyby\Translation\Translator $translator)
  {
    $this->translator = $translator;
  }

  /**
    * loadState
    * @param  array
    */
  public function loadState(array $params)
  {
    parent::loadState($params);
    $this->getPaginator()->page = $this->page;
    $this->getPaginator()->setItemsPerPage($this->items_per_page);
  }

  /**
    * render
    */
  public function render()
  {
    $this->verifyingData();

    if($this->canSetItemsPerPage)
      $this["itemsPerPage"]->setDefaults(["items_per_page" => $this->paginator->getItemsPerPage()]);


    if($this->paginator->pageCount<2)
      $arr = array($this->paginator->page);

    else
    {
      $arr = range(max($this->paginator->firstPage,$this->paginator->page-3),min($this->paginator->lastPage,$this->paginator->page+3));
      $count = 4;
      $quotient = ($this->paginator->pageCount-1)/$count;
      for($i=0; $i<=$count; $i++)
        $arr[] = round($quotient*$i)+$this->paginator->firstPage;

      sort($arr);
    }

    $this->template->setFile($this->paginatorTemplate);
    $this->template->paginator = $this->paginator;
    $this->template->steps = array_values(array_unique($arr));
    $this->template->items_per_page = $this->canSetItemsPerPage;
    $this->template->render();
  }

  /**
    * itemsPerPage component
    * @return Nette\Application\UI\Form
    */
  protected function createComponentItemsPerPage()
  {
    $form = new Form;
    //$form->getElementPrototype()->class("ajax");

    $form->addSelect("items_per_page",$this->translator->translate("paginator.items_per_page"),$this->itemsPerPageList)
      ->setAttribute("onchange","this.form.submit()")
      ->setRequired();

    $form->addSubmit("set","Nastavit");

    $form->onSuccess[] = $this->itemsPerPageSuccess;

    return $form;
  }

  /**
    * @param Nette\Application\UI\Form
    */
  public function itemsPerPageSuccess(Form $form)
  {
    $this->redirect("this",["items_per_page" => $form->getValues()->items_per_page]);
  }

  /**
    * verifyingData
    */
  private function verifyingData()
  {
    if($this->canSetItemsPerPage && !in_Array($this->getItemsPerPage(),$this->itemsPerPageList))
      throw new \Nette\InvalidArgumentException("Items per page list haven't value '".$this->getItemsPerPage()."', which you set in 'setItemsPerPage()' option.");
  }
}
