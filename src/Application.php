<?php

namespace N_ONE\App;

use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\DependencyInjection\DependencyInjection;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class Application
{
	private static null|DependencyInjection $di = null;

	public static function run(): void
	{
		if (self::$di === null)
		{
			$di = new DependencyInjection(Configurator::option('SERVICES_PATH'));
			self::$di = $di;
		}
		if (Configurator::option('MIGRATION_NEEDED'))
		{
			$migrator = self::$di->getComponent('migrator');
			$migrator->migrate();
		}

		$route = Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		if (!$route)
		{
			http_response_code(404);
			echo(TemplateEngine::renderAdminError(404, "Page not found"));
			exit;
		}
		$action = $route->action;
		$variables = $route->getVariables();
		echo $action(...$variables);
	}

	public static function getDI(): DependencyInjection
	{
		if (self::$di === null)
		{
			$di = new DependencyInjection(Configurator::option('SERVICES_PATH'));

			return self::$di = $di;
		}

		return self::$di;
	}

	private static function setDI($di): void
	{
		self::$di = $di;
	}
}