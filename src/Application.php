<?php

namespace N_ONE\App;

use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class Application
{
	static private ?Application $instance = null;

	private function __construct()
	{
	}

	public static function getInstance(): Application
	{
		if (static::$instance)
		{
			return static::$instance;
		}

		return static::$instance = new self();
	}

	public function run(): void
	{
		$route = Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		if (!$route)
		{
			http_response_code(404);
			echo (new TemplateEngine())->renderError(404, "Page not found");
			exit;
		}
		$action = $route->action;
		$variables = $route->getVariables();
		echo $action(...$variables);
	}

	private function __clone()
	{
	}
}