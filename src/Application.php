<?php

namespace N_ONE\App;

use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class Application
{
	public function run()
	{
		// echo "app started" . PHP_EOL;
		$route = Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		if ($route)
		{
			$action = $route->action;
			$variables = $route->getVariables();

			echo $action(...$variables);
		}
		else
		{
			http_response_code(404);
			echo (new TemplateEngine(__DIR__ . '../../src/View/'))->renderError(404, "Page not found");
			exit;
		}
	}
}