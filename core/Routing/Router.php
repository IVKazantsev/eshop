<?php

namespace N_ONE\Core\Routing;

class Router
{

	private static array $routes = [];

	public static function get(string $uri, callable $action): void
	{
		self::add('GET', $uri, $action);
	}

	public static function add(string $method, string $uri, callable $action): void
	{
		// self::$routes[] = new Route($method, $uri, $action(...));
		self::$routes[] = new Route($method, $uri, function() use ($action) {
			$route = self::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
			if ($route instanceof Route)
			{
				return $action($route);
			}
			throw new Exception("Route not found");
		});
	}

	public static function find(string $method, string $uri)
	{
		[$path] = explode('?', $uri);
		foreach (self::$routes as $route)
		{
			if ($route->method !== $method)
			{
				continue;
			}
			if ($route->match($path))
			{
				return $route;
			}
		}

		return null;
	}

	public static function post(string $uri, callable $action): void
	{
		self::add('POST', $uri, $action);
	}
}