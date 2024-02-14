<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Entity;
use RuntimeException;

class TagRepository extends Repository
{
	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];

		$result = mysqli_query(
			$connection,
			"
		SELECT t.ID, t.TITLE, t.PARENT_ID 
		FROM N_ONE_TAGS t;
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID'],
				null,
			);
		}

		if (empty($tags))
		{
			throw new RuntimeException("Items not found");
		}

		return $tags;
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
	public function getById(int $id): Tag
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
		SELECT t.ID, t.TITLE, t.PARENT_ID, it.VALUE
		FROM N_ONE_TAGS t
		join bitcar.N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
		WHERE t.ID = $id;
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		$tag = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$tag = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID'],
				$row['VALUE']
			);
		}

		if ($tag === null)
		{
			throw new RuntimeException("Item with id $id not found");
		}

		return $tag;
	}

	public function getByTitle(string $title): Tag
	{
		$connection = $this->dbConnection->getConnection();
		$title = mysqli_real_escape_string($connection, $title);

		$result = mysqli_query(
			$connection,
			"
		SELECT t.ID, t.TITLE, t.PARENT_ID, it.VALUE
		FROM N_ONE_TAGS t
		join bitcar.N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
		WHERE t.TITLE = '$title'
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		$tag = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$tag = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID'],
				$row['VALUE']
			);
		}

		if ($tag === null)
		{
			throw new RuntimeException("Item with title $title not found");
		}

		return $tag;
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
		$tags = [];

		$result = mysqli_query(
			$connection,
			"
		SELECT it.ITEM_ID, t.ID, t.TITLE, t.PARENT_ID, it.VALUE
		FROM N_ONE_TAGS t 
		JOIN N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
		WHERE it.ITEM_ID IN ($itemsIdsString);
	"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[$row['ITEM_ID']][] = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID'],
				$row['VALUE']
			);
		}

		if(empty($tags))
		{
			foreach ($itemsIds as $itemsId)
			{
				$tags[$itemsId] = [];
			}
		}
		return $tags;
	}

	public function add(Tag|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$parentId = $entity->getParentId();
		$result = mysqli_query(
			$connection,
			"
		INSERT INTO N_ONE_TAGS (ID, TITLE, PARENT_ID)
		VALUES (
			$tagId,
			'$title',
			$parentId
		);"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}

	public function update(Tag|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$parentId = $entity->getParentId();
		$result = mysqli_query(
			$connection,
			"
		UPDATE N_ONE_TAGS 
		SET 
			TITLE = '$title',
			PARENT_ID = $parentId
		WHERE ID = $tagId"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
}