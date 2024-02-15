<?php

namespace N_ONE\App\Model\Service;

use Closure;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;

class MiddleWare
{
	public static function adminMiddleware(callable $action): Closure
	{
		return static function(Route $route) use ($action) {
			session_start();
			if (!isset($_SESSION['user_id']))
			{
				Router::redirect('/login');
				exit();
			}

			return $action($route);
		};
	}
}