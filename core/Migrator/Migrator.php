<?php

namespace N_ONE\Core\Migrator;

use DateTime;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\DbConnector\DbConnector;
use RuntimeException;

class Migrator
{
	static private ?Migrator $instance = null;
	private DbConnector $dbConnector;

	private function __construct(DbConnector $dbConnector)
	{
		$this->dbConnector = $dbConnector;
	}

	private function __clone()
	{
	}

	public static function getInstance(): Migrator
	{
		if (static::$instance)
		{
			return static::$instance;
		}

		$dbConnector = DbConnector::getInstance();

		return static::$instance = new self($dbConnector);
	}

	public function migrate(): void
	{

		// 1. смотрим последнюю применённую миграцию, которая записана в таблице migration (если таблица пуста то делаем все миграции)
		$lastMigration = $this->getLastMigration();

		// 2. проходимся по /core/Migration/migrations и ищем новые миграции
		$newMigrations = $this->findNewMigrations($lastMigration);

		// 3. выполняем новые миграции
		foreach ($newMigrations as $migration)
		{
			$this->executeMigration($migration);
			$this->updateLastMigration($migration);
		}
	}

	private function getLastMigration()
	{
		$migrationTable = "N_ONE_MIGRATIONS";
		$connection = $this->dbConnector->getConnection();

		$tableExistsQuery = mysqli_query($connection, "SHOW TABLES LIKE '{$migrationTable}'");

		if (mysqli_num_rows($tableExistsQuery) === 0)
		{
			return null; // Возвращаем null, если таблица отсутствует
		}

		$result = mysqli_query(
			$connection,
			"
        SELECT *
        FROM {$migrationTable}
        ORDER BY ID DESC
        LIMIT 1
        "
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		// Если результат пустой, также возвращаем null
		return mysqli_fetch_assoc($result)["TITLE"];
	}

	private function findNewMigrations($lastMigration): array
	{
		$pattern = '/(\d{4}_\d{2}_\d{2}_\d{2}_\d{2})/';
		$migrations = [];
		$files = glob(ROOT . Configurator::option('MIGRATION_PATH') . '/*.sql');

		preg_match($pattern, $lastMigration, $matches);
		$currentTimestamp = ($matches) ? DateTime::createFromFormat('Y_m_d_H_i', $matches[0])->getTimestamp() : 0;

		foreach ($files as $file)
		{
			$filename = basename($file);
			// Ищем соответствие паттерну в строке пути к файлу
			if (preg_match($pattern, $file, $matches))
			{
				$timestamp = DateTime::createFromFormat('Y_m_d_H_i', $matches[0])->getTimestamp();

				if ($timestamp > $currentTimestamp)
				{
					$migrations[] = $filename;
				}
			}
		}

		return $migrations;
	}

	private function executeMigration($migration): void
	{

		// Получение соединения с базой данных
		$connection = $this->dbConnector->getConnection();

		// Чтение содержимого SQL файла
		$sql = file_get_contents(ROOT . Configurator::option('MIGRATION_PATH') . '/' . $migration);

		if (!$sql)
		{
			throw new RuntimeException("Failed to read migration file: $migration");
		}

		$queries = explode(';', $sql);

		foreach ($queries as $query)
		{
			// Удаляем лишние пробелы и символы перевода строки
			$query = trim($query);

			if (!empty($query))
			{
				// Выполнение SQL запроса
				$result = mysqli_query($connection, $query);

				if (!$result)
				{
					throw new RuntimeException(mysqli_error($connection));
				}
			}
		}
	}

	private function updateLastMigration($migration): void
	{
		$connection = $this->dbConnector->getConnection();

		$sql = "INSERT INTO N_ONE_MIGRATIONS (TITLE) VALUE ('{$migration}');";

		$result = mysqli_query($connection, $sql);
		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}
	}

}




