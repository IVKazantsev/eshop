<?php

namespace N_ONE\Core\DbConnector;

use N_ONE\Core\Configurator\Configurator;

class DbConnector
{
	private static \mysqli|false $connection;

	public function __construct()
	{
		$dbOptions = Configurator::option("DB_OPTIONS");
		$this->createConnection($dbOptions);
	}
	private function createConnection( $dbOptions)
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
			throw new \Exception($error);
		}

		$encodingResult = mysqli_set_charset(static::$connection, 'utf8');
		if (!$encodingResult)
		{
			throw new \Exception(mysqli_error(static::$connection));
		}
	}

	public function getConnection()
	{
		return self::$connection;
	}
}
