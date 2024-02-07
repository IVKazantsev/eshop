<?php

namespace N_ONE\App;

use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\DependencyInjection\DependencyInjection;
use N_ONE\Core\Migrator\Migrator;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class Application
{
	private static ?DependencyInjection $di = null;
	public static function run(): void
	{
		$di = new DependencyInjection(Configurator::option('SERVICES_PATH'));
		self::setDI($di);
		if (Configurator::option('MIGRATION_NEEDED'))
		{
			$migrator = self::$di->getComponent('migrator');
			$migrator->migrate();
		}

		$route = Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		if (!$route)
		{
			http_response_code(404);
			echo (TemplateEngine::renderError(404, "Page not found"));
			exit;
		}
		$action = $route->action;
		$variables = $route->getVariables();
		echo $action(...$variables);
	}



	private static function setDI($di): void
	{
		self::$di = $di;
	}

	public static function getDI(): ?DependencyInjection
	{
		return self::$di;
	}
}