<?php

namespace N_ONE\App;

use N_ONE\Core\Routing\Router;

class Application
{
	public function run()
	{
		echo 'app started';
		$route = Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

	}
}