<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Attribute;
use N_ONE\App\Model\Entity;
use RuntimeException;

class AttributeRepository extends Repository
{

	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$attributes = [];

		$result = mysqli_query(
			$connection,
			"
		SELECT a.ID, a.TITLE
		FROM N_ONE_ATTRIBUTES a;
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$attributes[] = new Attribute(
				$row['ID'],
				$row['TITLE'],
				null
			);
		}

		if (empty($attributes))
		{
			throw new RuntimeException("Items not found");
		}

		return $attributes;
	}


	// public function getById(int $id): Tag
	// {
	// 	$connection = $this->dbConnection->getConnection();
	//
	// 	$result = mysqli_query(
	// 		$connection,
	// 		"
	// 	SELECT t.ID, t.TITLE
	// 	FROM N_ONE_TAGS t
	// 	WHERE t.ID = $id;
	// 	"
	// 	);
	//
	// 	if (!$result)
	// 	{
	// 		throw new RuntimeException(mysqli_error($connection));
	// 	}
	//
	// 	$tag = null;
	// 	while ($row = mysqli_fetch_assoc($result))
	// 	{
	// 		$tag = new Tag(
	// 			$row['ID'], $row['TITLE'],
	// 		);
	// 	}
	//
	// 	if ($tag === null)
	// 	{
	// 		throw new RuntimeException("Item with id $id not found");
	// 	}
	//
	// 	return $tag;
	// }
	public function getById(int $id): Attribute
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
			SELECT a.ID, a.TITLE 
			FROM N_ONE_ATTRIBUTES a 
			WHERE a.ID = $id;
			"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		$attribute = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$attribute = new Attribute(
				$row['ID'],
				$row['TITLE'],
				null
			);
		}

		if ($attribute === null)
		{
			throw new RuntimeException("Item with id $id not found");
		}

		return $attribute;
	}

	public function getByTitle(string $title): Attribute
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
			throw new RuntimeException(mysqli_error($connection));
		}

		$attribute = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$attribute = new Attribute(
				$row['ID'],
				$row['TITLE'],
				null
			);
		}

		if ($attribute === null)
		{
			throw new RuntimeException("Item with title $title not found");
		}

		return $attribute;
	}

	/**
	 * @param int[] $itemsIds
	 */
	// public function getByItemsIds(array $itemsIds): array
	// {
	// 	$connection = $this->dbConnection->getConnection();
	// 	$itemsIdsString = implode(',', $itemsIds);
	// 	$tags = [];
	//
	// 	$result = mysqli_query(
	// 		$connection,
	// 		"
	// 	SELECT it.ITEM_ID, t.TITLE
	// 	FROM N_ONE_TAGS t
	// 	JOIN N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
	// 	WHERE it.ITEM_ID IN ($itemsIdsString);
	// "
	// 	);
	//
	// 	if (!$result)
	// 	{
	// 		throw new RuntimeException(mysqli_error($connection));
	// 	}
	//
	// 	while ($row = mysqli_fetch_assoc($result))
	// 	{
	// 		$tags[$row['ITEM_ID']][] = new Tag($row['ID'], $row['TITLE'],);
	// 	}
	//
	// 	return $tags;
	// }
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
			WHERE ia.ITEM_ID IN ($itemsIdsString);
			"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$attributes[$row['ITEM_ID']][] = new Attribute(
				$row['ID'],
				$row['TITLE'],
				$row['VALUE'],
			);
		}

		if(empty($attributes))
		{
			foreach ($itemsIds as $itemsId)
			{
				$attributes[$itemsId] = [];
			}
		}
		return $attributes;
	}

	public function add(Attribute|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$attributeId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_ATTRIBUTES (ID, TITLE)
			VALUES (
				$attributeId,
				'$title',
			);"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}

	public function update(Attribute|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$attributeId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_ATTRIBUTES 
			SET TITLE = '$title',
			WHERE ID = $attributeId
			"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
}