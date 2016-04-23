<?php

namespace Game\Router;

use Nette\Application\Routers\Route;



class RouterFactory extends \Teddy\Router\RouterFactory
{

	/**
	 * @warning: annotation is needed!
	 *
	 * @return \Nette\Application\IRouter
	 */
	public static function create()
	{
		Route::$defaultFlags = Route::SECURED; // needed for WebRTC
		$router = parent::create();
		return $router;
	}

}
