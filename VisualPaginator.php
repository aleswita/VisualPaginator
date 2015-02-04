<?php

namespace App\Components;

use Nette\Utils\Paginator,
    Nette\Application\UI\Control,
    Nette\Application\UI\Form;

/**
  * @author Ales Wita
  */
class VisualPaginator extends Control
{
  /** @persistent */
  public $page = 1;

  /** @persistent */
  public $items_per_page = 20;

  /** @var paginator */
  private $paginator;

  /** @var canSetItemsPerPage */
  private $canSetItemsPerPage = false;

  /** @var itemsPerPageList */
  private $itemsPerPageList = [10 => "10",20 => "20",30 => "30",40 => "40",50 => "50",100 => "100"];

  /**
    * __construct
    */
  public function __construct()
  {
    $this->paginator = new Paginator;
  }

  /**
    * setItemCount
    * @param int
    * @return self
    */
  public function setItemCount($data)
  {
    return $this->paginator->setItemCount($data);
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
    * setItemsPerPage
    * @return int
    */
  public function getOffset()
  {
    return $this->paginator->getOffset();
  }

  /**
    * canSetItemsPerPage
    * @param bool or array
    * @return self
    */
  public function canSetItemsPerPage($data=true)
  {
    if(is_Array($data))
      $this->itemsPerPageList = $data;

    return $this->canSetItemsPerPage = true;
  }

  /**
    * render
    */
  public function render()
  {
    $this->paginator->setPage($this->page);
    $this->paginator->setItemsPerPage($this->items_per_page);

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

    $this->template->setFile(__DIR__."/template.latte");
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
    $form->addProtection();

    $form->addSelect("items_per_page","Položek na stránku",$this->itemsPerPageList)
      ->setAttribute("onchange","this.form.submit()")
      ->setRequired();

    $form->addSubmit("set","Nastavit");

    $form->onSuccess[] = $this->itemsPerPageSuccess;

    return $form;
  }

  /**
    * @param \Nette\Application\UI\Form
    */
  public function itemsPerPageSuccess(Form $form)
  {
    $this->redirect("this",["items_per_page" => $form->getValues()->items_per_page]);
  }
}
