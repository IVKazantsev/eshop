<?php

namespace N_ONE\Core\DbConnector;

use Exception;
use mysqli;
use N_ONE\Core\Configurator\Configurator;
use RuntimeException;

class DbConnector
{
	static private ?DbConnector $instance = null;

	/**
	 * @throws Exception
	 */
	private function __construct()
	{
		$dbOptions = Configurator::option("DB_OPTIONS");
		$this->createConnection($dbOptions);
	}

	private function __clone()
	{
	}

	public static function getInstance(): DbConnector
	{
		if (static::$instance)
		{
			return static::$instance;
		}

		return static::$instance = new self();
	}

	private static mysqli|false $connection;

	/**
	 * @throws Exception
	 */
	private function createConnection($dbOptions): void
	{
		$dbHost = $dbOptions["DB_HOST"];
		$dbUser = $dbOptions["DB_USER"];
		$dbPassword = $dbOptions["DB_PASSWORD"];
		$dbName = $dbOptions["DB_NAME"];

		static::$connection = mysqli_init();

		$connected = mysqli_real_connect(static::$connection, $dbHost, $dbUser, $dbPassword, $dbName);
		if (!$connected)
		{
			$error = mysqli_connect_errno() . ': ' . mysqli_connect_error();
			throw new RuntimeException($error);
		}

		$encodingResult = mysqli_set_charset(static::$connection, 'utf8');
		if (!$encodingResult)
		{
			throw new RuntimeException(mysqli_error(static::$connection));
		}
	}

	public function getConnection(): bool|mysqli
	{
		return self::$connection;
	}
}
