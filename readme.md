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

	public function renderDefault(): void {
		$dataSource = $this->model->dataSource();
		$this["paginator"]->setItemCount($dataSource->count());
		$dataSource->applyLimit($this["paginator"]->getItemsPerPage(), $this["paginator"]->getOffset());

		$this->template->items = $dataSource;
	}

	/**
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginator(): AlesWita\Components\VisualPaginator {
		$vp = new VisualPaginator;
		// paginator have 3 predefined templates: TEMPLATE_NORMAL, TEMPLATE_BOOTSTRAP_V3 and TEMPLATE_BOOTSTRAP_V4	
		$vp->setPaginatorTemplate(VisualPaginator::TEMPLATE_BOOTSTRAP_V3);
		
		return $vp;
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
$vp->setCanSetItemsPerPage(TRUE);
```
You can set your choices (remember, keys in array must be numeric):
```php
$vp->setItemsPerPageList([10 => "10", 15 => "15"]);
```

#### Session
If you set **Nette\Http\Session** object, paginator save the value from **items per page** form to session:
```php
$vp->setSession($this->session);
```
If you have more than one paginators on your page, items per page saved separated by module / presenter / action. For all paginator saved to one property, use second parameter in **setSession** method:
```php
$vp->setSession($this->session, "paginator");
```
Session namespace for paginator in default is "Visual-Paginator", for change you can use third parameter in **setSession** method:
```php
$vp->setSession($this->session, "paginator", "my-namespace");
```

#### Ajax
If you are like using **ajax** for paginate, don't worry and enabled ajax to true:
```php
$vp->setAjax(TRUE);
```
And set **onPaginate[]** callback for redraw your snippets:
```php
$vp->onPaginate[] = function(){
	if ($this->isAjax()) {
		$this->redrawControl("snippet");
	}
};
```

#### Translations
Paginator have accepted **Nette\Localization\ITranslator** for translators:
```php
$vp->setTranslator($this->translator);
```
For changing the pre-defined texts:
```php
$vp->setText("send", "paginator.send")
	->setText("itemsPerPage", "paginator.itemsPerPage");
```

## Configuration by DI
Setup in **config.neon**:
```neon
extensions:
	visualpaginator: AlesWita\Components\VisualPaginatorExtension
	
visualpaginator:
	session: @Nette\Http\Session
	translator: @Nette\Localization\ITranslator
	template: @AlesWita\Components\VisualPaginator::TEMPLATE_BOOTSTRAP_V4
	texts: [
		["send", "Send"],
		["itemsPerPage", "Items per page"]
	]
```
And usage in presenter:
```php
use AlesWita\Components\VisualPaginator;


final class HomePresenter extends BasePresenter
{
	/** @var AlesWita\Components\VisualPaginator @inject */
	public $visualPaginator;

	...
	...

	/**
	 * @return AlesWita\Components\VisualPaginator
	 */
	protected function createComponentPaginator(): AlesWita\Components\VisualPaginator {
		$vp = $this->visualPaginator;

		$vp->setCanSetItemsPerPage(TRUE)
			->setAjax(TRUE);

		return $vp;
	}
}
```
