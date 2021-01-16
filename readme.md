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
use AlesWita\VisualPaginator\VisualPaginator;
use AlesWita\VisualPaginator\VisualPaginatorFactory;
use Nette\Application\UI\Presenter;

final class HomePresenter extends Presenter
{

	/** @inject */
	public VisualPaginatorFactory $visualPaginatorFactory;

	protected function createComponentPaginator(): VisualPaginator
	{
		$paginator = $this->visualPaginatorFactory->create();

		$paginator->setItemCount(1000);

		return $paginator;
	}

}
```

#### Template
```html
{control paginator}
```
