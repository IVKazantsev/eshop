<?php

namespace N_ONE\Core\Routing;

class Router
{

	static private ?Router $instance = null;

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	public static function getInstance(): Router
	{
		if (static::$instance)
		{
			return static::$instance;
		}

		return static::$instance = new self();
	}

	public static array $routes = [];

	public static function get(string $uri, callable $action): void
	{
		self::add('GET', $uri, $action);
	}

	public static function add(string $method, string $uri, callable $action): void
	{
		self::$routes[] = new Route($method, $uri, $action(...));
	}

	public static function post(string $uri, callable $action): void
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