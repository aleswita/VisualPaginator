# Visual Paginator
Visual Paginator for Nette Framework.

[![Build Status](https://github.com/aleswita/VisualPaginator/workflows/build/badge.svg)](https://github.com/aleswita/VisualPaginator/actions?query=workflow%3Abuild)
[![Coverage Status](https://coveralls.io/repos/github/aleswita/VisualPaginator/badge.svg?branch=master)](https://coveralls.io/github/aleswita/VisualPaginator?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

## Installation
The best way to install AlesWita/VisualPaginator is using [Composer](http://getcomposer.org/):
```sh
$ composer require aleswita/visualpaginator
```

## Usage
#### Neon
```yaml
services:
	- AlesWita\VisualPaginator\VisualPaginatorFactory
```

#### Presenter
```php
<?php declare(strict_types = 1);

use AlesWita\VisualPaginator\VisualPaginator;
use AlesWita\VisualPaginator\VisualPaginatorFactory;
use Nette\Application\UI\Presenter;

final class HomePresenter extends Presenter
{

	/** @inject */
	public VisualPaginatorFactory $visualPaginatorFactory;

	public function actionDefault(): void
	{
	    $this['paginator']->setItemCount(1000);
	    $offset = $this['paginator']->getOffset();
	    $itemsPerPage = $this['paginator']->getItemsPerPage();

	    ['SELECT * FROM `orders` LIMIT ? OFFSET ?', $itemsPerPage, $offset];
	}

	protected function createComponentPaginator(): VisualPaginator
	{
		$paginator = $this->visualPaginatorFactory->create();

		$paginator->ajax = true;
		$paginator->canSetItemsPerPage = true;
		$paginator->templateFile = __DIR__ . '/my_awesome_template.latte';

		return $paginator;
	}

}
```

#### Template
```html
{control paginator}
```

#### Custom paginator template
```html
{templateType AlesWita\VisualPaginator\Template}
...
```
