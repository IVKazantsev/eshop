<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Exceptions\DatabaseException;

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
	abstract public function getList(array $filter = null): array;

	/**
	 * @throws DatabaseException
	 */
	public function delete(string $entities, int $entityId): bool
	{
		$connection = $this->dbConnection->getConnection();
		$entities = mysqli_real_escape_string($connection, $entities);

		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_$entities 
			SET IS_ACTIVE = 0
			WHERE ID = $entityId"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}
}