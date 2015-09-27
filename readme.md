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
		// VisualPaginator have 2 predefined templates: TEMPLATE_NORMAL and TEMPLATE_BOOTSTRAP_V3
		return $visualPaginator = new VisualPaginator(VisualPaginator::TEMPLATE_BOOTSTRAP_V3);
	}
}
```

#### Template
```html
{control paginator}
```


## More options

#### Items per page
Visitors can select from list, how many items shows. Predefined values for choice are 10, 20, 30, 40, 50 and 100:
```php
$visualPaginator->canSetItemsPerPage();
```
You can set your choices by using first parameter (remember, keys in array must be numeric):
```php
$visualPaginator->canSetItemsPerPage([10 => "10", 15 => "15"]);
// second choice
$visualPaginator->setItemsPerPageList([10 => "10", 15 => "15"]);
```
If you use second parameter in **canSetItemsPerPage** or **setItemsPerPageList** methods, array merged with already setted array:
```php
$visualPaginator->canSetItemsPerPage([60 => "60", 200 => "200"], TRUE);
```
Result is [10 => "10", 20 => "20", 30 => "30", 40 => "40", 50 => "50", **60 => "60"**, 100 => "100", **200 => "200"**].

#### Session
If you set **Nette\Http\Session** object, paginator save the value from **items per page** form to session:
```php
$visualPaginator->setSession($this->session);
```
If you have more than one paginators on your page, items per page saved separated by module / presenter / action. For all paginator saved to one property, use second parameter in **setSession** method:
```php
$visualPaginator->setSession($this->session, "paginator");
// second choice
$visualPaginator->setItemsPerPageReposity("paginator");
```
Session namespace for paginator in default is "Visual-Paginator", for change you can use third parameter in **setSession** method:
```php
$visualPaginator->setSession($this->session, "paginator", "my-namespace");
```

#### Ajax
If you are like using **ajax** for paginate, don't worry and set snippets for redraw:
```php
$visualPaginator->setSnippet("table");
// second choice
$visualPaginator->setSnippet(["table", "menu"]);
// third choice
$visualPaginator->setSnippets(["table", "menu"]);
```
And if you need disabled ajax, use **setAjax** method (do not use this methot for enable, because if you set some snippet, paginator enable ajax automatically):
```php
$visualPaginator->setAjax(FALSE);
```

#### Language
Because that much people using different translators and methods for multi-language applications, so paginator have special method for translate their texts:
```php
$visualPaginator->setText("send", "Odeslat")
	->setText("itemsPerPage", "Položek na stránku");
```

#### Template
For custom template use **setPaginatorTemplate** method:
```php
$visualPaginator->setPaginatorTemplate(__DIR__ . "/template.latte");
```
