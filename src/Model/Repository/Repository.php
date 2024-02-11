<?php

namespace N_ONE\App\Model\Repository;

use Exception;
use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;

abstract class Repository
{
	public function __construct(protected readonly DbConnector $dbConnection)
	{
	}

	abstract public function getById(int $id): ?Entity;

	abstract public function add(Entity $entity): int;

	abstract public function update(Entity $entity): bool;

	/**
	 * @return Entity[]
	 */
	public function getListOrFail(array $filter = []): array
	{
		$items = $this->getList($filter);

		if (empty($items))
		{
			echo 'Nothing to do here' . PHP_EOL;
			exit();
		}

		return $items;
	}

	/**
	 * @return Entity[]
	 */
	abstract public function getList(array $filter = null): array;

	public function delete(string $entities, int $entityId): bool
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
		UPDATE N_ONE_$entities 
		SET IS_ACTIVE = 0
		WHERE ID = $entityId"
		);

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		return true;
	}
}