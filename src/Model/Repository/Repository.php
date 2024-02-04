<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Entity;

abstract class Repository
{
	abstract public function getById(int $id): ?Entity;

	abstract public function add(Entity $entity): bool;

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
}