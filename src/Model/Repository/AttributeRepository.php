<?php

namespace N_ONE\App\Model\Repository;

use mysqli_sql_exception;
use N_ONE\App\Model\Attribute;
use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use RuntimeException;

class AttributeRepository extends Repository
{

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$numItemsPerPage = Configurator::option('NUM_OF_ITEMS_PER_PAGE');
		$currentLimit = $numItemsPerPage + 1;
		$offset = ($filter['pageNumber'] ?? 0) * $numItemsPerPage;
		$isActive = $filter['isActive'] ?? 1;
		$attributes = [];

		$whereQueryBlock = $this->getWhereQueryBlock($isActive);

		$result = mysqli_query(
			$connection,
			"
			SELECT a.ID, a.TITLE
			FROM N_ONE_ATTRIBUTES a
			$whereQueryBlock
			LIMIT $currentLimit OFFSET $offset;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$attributes[] = new Attribute(
				$row['ID'], $row['TITLE'], null
			);
		}

		return $attributes;
	}

	private function getWhereQueryBlock(int $isActive): string
	{
		return "WHERE a.IS_ACTIVE = $isActive";
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getById(int $id, bool $isPublic = false): ?Attribute
	{
		$connection = $this->dbConnection->getConnection();
		$attribute = null;
		if ($isPublic)
		{
			$isActive = "(1)";
		}
		else
		{
			$isActive = "(1, 0)";
		}
		$result = mysqli_query(
			$connection,
			"
			SELECT a.ID, a.TITLE 
			FROM N_ONE_ATTRIBUTES a 
			WHERE a.ID = $id and a.IS_ACTIVE in $isActive;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$attribute = new Attribute(
				$row['ID'], $row['TITLE'], null
			);
		}

		return $attribute;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByTitle(string $title): ?Attribute
	{
		$connection = $this->dbConnection->getConnection();
		$title = mysqli_real_escape_string($connection, $title);

		$result = mysqli_query(
			$connection,
			"
			SELECT a.ID, a.TITLE, a.PARENT_ID
			FROM N_ONE_ATTRIBUTES a
			WHERE a.TITLE = '$title'
			"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$attribute = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$attribute = new Attribute(
				$row['ID'], $row['TITLE'], null
			);
		}

		return $attribute;
	}

	/**
	 * @param int[] $itemsIds
	 *
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */

	public function getByItemsIds(array $itemsIds): array
	{
		$connection = $this->dbConnection->getConnection();
		$itemsIdsString = implode(',', $itemsIds);
		$attributes = [];

		$result = mysqli_query(
			$connection,
			"
			SELECT ia.ITEM_ID, a.ID, a.TITLE, ia.VALUE
			FROM N_ONE_ATTRIBUTES a 
			JOIN N_ONE_ITEMS_ATTRIBUTES ia on a.ID = ia.ATTRIBUTE_ID
			WHERE ia.ITEM_ID IN ($itemsIdsString)
			AND a.IS_ACTIVE = 1
			order by a.ID;
			"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$attributes[$row['ITEM_ID']][] = new Attribute(
				$row['ID'], $row['TITLE'], $row['VALUE'],
			);
		}

		if (empty($attributes))
		{
			foreach ($itemsIds as $itemsId)
			{
				$attributes[$itemsId] = [];
			}
		}

		return $attributes;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function add(Attribute|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_ATTRIBUTES (TITLE)
			VALUE ('$title');"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function update(Attribute|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$attributeId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_ATTRIBUTES 
			SET TITLE = '$title'
			WHERE ID = $attributeId
			"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}
}