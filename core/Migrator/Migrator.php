<?php

namespace N_ONE\Core\Migrator;

use DateTime;
use Exception;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\DbConnector\DbConnector;

class Migrator
{
	private DbConnector $dbConnection;
	private string $migrationPath;
	private string $migrationTable;
	public function __construct(DbConnector $dbConnection)
	{
		$this->dbConnection = $dbConnection;
		$this->migrationPath = Configurator::option('MIGRATION_PATH');
		$this->migrationTable = Configurator::option('MIGRATION_TABLE');
	}

    public function migrate()
    {

        // 1. смотрим последнюю применённую миграцию, которая записана в таблице migration (если таблица пуста то делаем все миграции)
        $lastMigration = $this->getLastMigration();

        // 2. проходимся по /core/Migrator/migrations и ищем новые миграции
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
		$connection = $this->dbConnection->getConnection();

		$tableExistsQuery = mysqli_query($connection, "SHOW TABLES LIKE '{$this->migrationTable}'");

		if (mysqli_num_rows($tableExistsQuery) === 0)
		{
			return null; // Возвращаем null, если таблица отсутствует
		}

		$result = mysqli_query($connection, "
        SELECT *
        FROM {$this->migrationTable}
        ORDER BY ID DESC
        LIMIT 1
        ");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		// Если результат пустой, также возвращаем null
		return mysqli_fetch_assoc($result)["TITLE"];
	}
	private function findNewMigrations($lastMigration)
	{
		$pattern = '/(\d{4}_\d{2}_\d{2}_\d{2}_\d{2})/';
		$migrations = [];
		$files = glob(ROOT . $this->migrationPath . '/*.sql');

		preg_match($pattern, $lastMigration, $matches);
		$currentTimestamp = ($matches) ? DateTime::createFromFormat('Y_m_d_H_i', $matches[0])->getTimestamp() : 0;

		foreach ($files as $file)
		{
			$filename = basename($file);
			// Ищем соответствие паттерну в строке пути к файлу
			if (preg_match($pattern, $file, $matches))
			{
				$timestamp = DateTime::createFromFormat('Y_m_d_H_i', $matches[0])->getTimestamp();

				if($timestamp > $currentTimestamp)
				{
					$migrations[] = $filename;
				}
			}
		}

		return $migrations;
	}
	private function executeMigration($migration)
	{

		// Получение соединения с базой данных
		$connection = $this->dbConnection->getConnection();

		// Чтение содержимого SQL файла
		$sql = file_get_contents(ROOT . $this->migrationPath . '/' . $migration);

		if (!$sql)
		{
			throw new Exception("Failed to read migration file: $migration");
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
					throw new Exception(mysqli_error($connection));
				}
			}
		}
	}

	private function updateLastMigration($migration)
	{
		$connection = $this->dbConnection->getConnection();

		$sql = "INSERT INTO {$this->migrationTable} (TITLE) VALUE ('{$migration}');";

		$result = mysqli_query($connection, $sql);
		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}
	}

}




