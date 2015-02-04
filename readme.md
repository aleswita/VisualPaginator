# VisualPaginator
Visual Paginator for Nette Framework and Bootstrap

## Usage
#### Presenter
```php
final class HomePresenter extends BasePresenter
{
  ...
  ...

  /**
    * renderDefault
    */
  public function renderDefault()
  {
    $dataSource = $this->model->dataSource();
    $this["paginator"]->setItemCount($dataSource->count());
    $dataSource->applyLimit($this["paginator"]->getItemsPerPage(),$this["paginator"]->getOffset());

    $this->template->items = $dataSource;
  }

  /**
    * paginator component
    * @return AlesWita\Components\VisualPaginator
    */
  protected function createComponentPaginator()
  {
    return $visualPaginator = new \AlesWita\Components\VisualPaginator;
  }
}
```
#### Template
```html
{control paginator}
```


#### More options
You can use option, where you can change count items per page:
```php
$visualPaginator->canSetItemsPerPage();
```

If you does not predefined options, you can change it:
```php
$visualPaginator->canSetItemsPerPage([10 => 10,12 => 12,15 => 15]);
```

..and you can change default values for page and count items per page
```php
$visualPaginator->setDefaultPage(5);
$visualPaginator->setDefaultItemsPerPage(100);
```
