<?php

/**
 * This file is part of the AlesWita\Components\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\VisualPaginator\Tests\App;

use Nette;


final class Router
{
	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter(): Nette\Application\IRouter {
		$route = new Nette\Application\Routers\RouteList;
		$route[] = new Nette\Application\Routers\Route("<presenter>/<action>[/<id>]", "Test:default");
		return $route;
	}
}
