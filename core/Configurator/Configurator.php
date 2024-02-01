<?php

namespace N_ONE\Core\Configurator;

use RuntimeException;

class Configurator
{

	static private Configurator $instance;

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	public static function getInstance(): Configurator
	{
		if (static::$instance)
		{
			return static::$instance;
		}

		return static::$instance = new self();
	}

	public static function option(string $name, $defaultValue = null)
	{
		static $config = null;

		if ($config === null)
		{
			$masterConfig = require ROOT . '/config/config.php';
			if (file_exists(ROOT . '/config/config.local.php'))
			{
				$localConfig = require ROOT . '/config/config.local.php';
			}
			else
			{
				$localConfig = [];
			}

			$config = array_merge($masterConfig, $localConfig);
		}

		if (array_key_exists($name, $config))
		{
			return $config[$name];
		}

		if ($defaultValue !== null)
		{
			return $defaultValue;
		}

		throw new RuntimeException("Configuration option {$name} not found");
	}
}