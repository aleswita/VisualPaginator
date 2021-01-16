<?php declare(strict_types = 1);

namespace App;

use Nette\Routing\Router;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	public static function createRouter(): Router
	{
		$route = new RouteList();
		$route->addRoute('<presenter>/<action>', 'Test:default');
		return $route;
	}
}
