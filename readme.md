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
use AlesWita\Components\VisualPaginator;


final class HomePresenter extends BasePresenter
{
	...
	...

	public function renderDefault()
	{
		$dataSource = $this->model->dataSource();
		$this["paginator"]->setItemCount($dataSource->count());
		$dataSource->applyLimit($this["paginator"]->getItemsPerPage(), $this["paginator"]->getOffset());

		$this->template->items = $dataSource;
	}

	/**
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginator()
	{
		// VisualPaginator have 3 predefined templates: TEMPLATE_NORMAL, TEMPLATE_BOOTSTRAP and TEMPLATE_BOOTSTRAP_AJAX
		return $visualPaginator = new VisualPaginator(VisualPaginator::TEMPLATE_BOOTSTRAP);
	}
}
```
#### Template
```html
{control paginator}
```


#### More options
You can set option, when user can set manualy items per page and you can use first parameter to change default options for items per page in select list:
```php
$visualPaginator->canSetItemsPerPage();
$visualPaginator->canSetItemsPerPage([10 => "10", 12 => "12", 15 => "15"]);
```

If you set **Nette\Http\Session** object, paginator save the value **items per page** to cookies (separated by module / presenter / action)
```php
$visualPaginator->setSession($this->session);
```

If you need special render template, you can use **setPaginatorTemplate** method:
```php
$visualPaginator->setPaginatorTemplate(__DIR__ . "/template.latte");
```

If you are like using **ajax** for paginate, don't worry and set snippets for redraw:
```php
$visualPaginator->setSnippet("table");
```
