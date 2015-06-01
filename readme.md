# Visual Paginator
Visual Paginator for Nette Framework.

##Installation
The best way to install AlesWita/VisualPaginator is using [Composer](http://getcomposer.org/):
```sh
$ composer require aleswita/visualpaginator:dev-master
```

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
You can set option, when user can set manualy items per page:
```php
$visualPaginator->canSetItemsPerPage();
```

You can change default options for items per page in select list:
```php
$visualPaginator->canSetItemsPerPage([10 => 10,12 => 12,15 => 15]);
```

You can set **Nette\Http\Session** to pernament save user items per page
```php
$visualPaginator->setSession($this->session);
```

You can change render template:
```php
$visualPaginator->setPaginatorTemplate(__DIR__."/template.latte");
```

You can change default values for page and count items per page:
```php
$visualPaginator->setDefaultItemsPerPage(100);
```
