<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
        $router[] = new Route('interprets/edit/[<id>]', array('presenter' => 'Interprets', 'action' => 'edit'));
        $router[] = new Route('interprets/detail/[<id>]', array('presenter' => 'Interprets', 'action' => 'detail'));
        $router[] = new Route('festivals/detail/[<id>]', array('presenter' => 'Festivals', 'action' => 'detail'));
        $router[] = new Route('concerts/detail/[<id>]', array('presenter' => 'Concerts', 'action' => 'detail'));
        $router[] = new Route('news/', array('presenter' => 'News', 'action' => 'default'));
        $router[] = new Route('notfound/', array('presenter' => 'Notfound', 'action' => 'default'));
        $router[] = new Route('concerts/', array('presenter' => 'Concerts', 'action' => 'default'));
        $router[] = new Route('festivals/', array('presenter' => 'Festivals', 'action' => 'default'));
        $router[] = new Route('interprets/', array('presenter' => 'Interprets', 'action' => 'default'));
        $router[] = new Route('<presenter>/<action>', array('presenter' => 'News', 'action' => 'default'));
        return $router;
	}
}
