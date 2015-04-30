<?php

namespace AlesWita\Components;

use Nette\Http\Session,
    Nette\Utils\Paginator,
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
  public $itemsPerPage = 10;

  /* ******************** */

  /** @var session */
  private $session;

  /** @var canSetItemsPerPage */
  private $canSetItemsPerPage = false;

  /** @var itemsPerPageList */
  private $itemsPerPageList = [10 => "10",20 => "20",30 => "30",40 => "40",50 => "50",100 => "100"];

  /** @var paginator */
  private $paginator;

  /** @var paginatorTemplate */
  private $paginatorTemplate;

  /** @var translator */
  private $translator;

  /* ******************** */

  // session section const
  const SESSION_SECTION = "AlesWita\\Components\\VisualPaginator";

  /* ******************** */

  /**
    * __construct
    */
  public function __construct()
  {
    $this->paginator = new Paginator;
    $this->paginatorTemplate = __DIR__."/template.latte";
  }

  /* ******************** */

  /**
    * @param Nette\Http\Session
    * @return self
    */
  public function setSession(Session $session)
  {
    $this->session = $session->getSection(self::SESSION_SECTION);
    return $this;
  }

  /**
    * @param array
    * @return self
    */
  public function canSetItemsPerPage($data=null)
  {
    if(is_Array($data)){
      $this->itemsPerPageList = $data;
    }

    $this->canSetItemsPerPage = true;
    return $this;
  }

  /**
    * @param int
    * @return self
    */
  public function setItemCount($data)
  {
    $this->paginator->setItemCount($data);
    return $this;
  }

  /**
    * @param int
    * @return self
    */
  public function setItemsPerPage($data)
  {
    $this->paginator->setItemsPerPage($data);
    return $this;
  }

  /**
    * @param int
    * @return self
    */
  public function setDefaultPage($data)
  {
    $this->page = $data;
    return $this;
  }

  /**
    * @param string
    * @return self
    */
  public function setPaginatorTemplate($data)
  {
    $this->paginatorTemplate = $data;
    return $this;
  }

  /**
    * @param translator
    * @return self
    */
  public function setTranslator(\Kdyby\Translation\Translator $translator)
  {
    $this->translator = $translator;
    return $this;
  }

  /* ******************** */

	/**
    * @return Nette\Utils\Paginator
    */
  public function getPaginator()
  {
    $this->paginator->setPage($this->page);
    $this->paginator->setItemsPerPage($this->itemsPerPage);
    return $this->paginator;
  }

  /**
    * @return int
    */
  public function getItemsPerPage()
  {
    if($this->session){
      $request = $this->presenter->getPresenter()->getRequest();
      $presenterName = $request->getPresenterName();

      if(isSet($this->session->$presenterName) && in_Array($this->session->$presenterName,$this->itemsPerPageList)){
        $this->paginator->setItemsPerPage($this->session->$presenterName);
      }else{
        unSet($this->session->$presenterName);
      }
    }

    return $this->paginator->getItemsPerPage();
  }

  /**
    * @return int
    */
  public function getOffset()
  {
    return $this->paginator->getOffset();
  }

  /* ******************** */

  /**
    * @param  array
    */
  public function loadState(array $params)
  {
    parent::loadState($params);

    $this->getPaginator()->setPage($this->page);
    $this->page = $this->getPaginator()->getPage();
    $this->getPaginator()->setItemsPerPage($this->itemsPerPage);
  }

  /**
    * render
    */
  public function render()
  {
    $this->verifyingData();

    if($this->canSetItemsPerPage){
      $this["itemsPerPage"]->setDefaults(["itemsPerPage" => $this->paginator->getItemsPerPage()]);
    }

    if($this->paginator->pageCount<2){
      $arr = array($this->paginator->page);
    }else{
      $arr = range(max($this->paginator->firstPage,$this->paginator->page-3),min($this->paginator->lastPage,$this->paginator->page+3));
      $count = 4;
      $quotient = ($this->paginator->pageCount-1)/$count;

      for($i=0; $i<=$count; $i++)
      {
        $arr[] = round($quotient*$i)+$this->paginator->firstPage;
      }

      sort($arr);
    }

    $this->template->setFile($this->paginatorTemplate);
    $this->template->paginator = $this->paginator;
    $this->template->steps = array_values(array_unique($arr));
    $this->template->itemsPerPage = $this->canSetItemsPerPage;
    $this->template->render();
  }

  /* ******************** */

  /**
    * itemsPerPage component
    * @return Nette\Application\UI\Form
    */
  protected function createComponentItemsPerPage()
  {
    $form = new Form;

    $form->addSelect("itemsPerPage",null,$this->itemsPerPageList)
      ->setAttribute("onchange","this.form.submit()")
      ->setRequired();

    $form->addSubmit("set");

    $form->onSuccess[] = $this->itemsPerPageSuccess;

    return $form;
  }

  /**
    * @param Nette\Application\UI\Form
    */
  public function itemsPerPageSuccess(Form $form,$values)
  {
    if($this->session){
      $request = $this->presenter->getPresenter()->getRequest();
      $presenterName = $request->getPresenterName();
      $this->session->$presenterName = $values->itemsPerPage;
    }

    if(!$this->presenter->isAjax()){
      $this->redirect("this",["itemsPerPage" => $values->itemsPerPage]);
    }
  }

  /**
    * verifyingData
    */
  private function verifyingData()
  {
    if($this->canSetItemsPerPage && !in_Array($this->getItemsPerPage(),$this->itemsPerPageList)){
      throw new \Nette\InvalidArgumentException("Items per page list haven't value '".$this->getItemsPerPage()."', which you set in 'setItemsPerPage()' option.");
    }
  }
}
