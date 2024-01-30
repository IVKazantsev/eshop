<?php

namespace N_ONE\Core\Routing;

class Router
{
	/**
	 * @var array Router[]
	 */
	public static array $routes = [];

	public static function get(string $uri, callable $action)
	{
		self::add('GET', $uri, $action);
	}

	public static function add(string $method, string $uri, callable $action)
	{
		self::$routes[] = new Route($method, $uri, \Closure::fromCallable($action));
	}

	public static function post(string $uri, callable $action)
	{
		self::add('POST', $uri, $action);
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
}