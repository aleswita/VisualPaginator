<?php declare(strict_types = 1);

namespace App;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;

final class RouterFactory
{

	public static function createRouter(): Router
	{
		$route = new RouteList();
		$route->addRoute('<presenter>/<action>', 'Test:default');
		return $route;
	}

}
