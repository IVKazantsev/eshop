<?php

namespace N_ONE\App\Model\Repository;

use Exception;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;

class TagRepository extends Repository
{
	private DbConnector $dbConnection;

	public function __construct(DbConnector $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];

		$result = mysqli_query($connection, "
		SELECT t.ID, t.TITLE
		FROM N_ONE_TAGS t;
		");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
			);
		}

		if (empty($tags))
		{
			throw new Exception("Items not found");
		}

		return $tags;
	}
	public function getById(int $id): Tag
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query($connection, "
		SELECT t.ID, t.TITLE
		FROM N_ONE_TAGS t
		WHERE t.ID = {$id};
		");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$tag = new Tag(
				$row['ID'],
				$row['TITLE'],
			);
		}

		if (empty($tag))
		{
			throw new Exception("Item with id {$id} not found");
		}

		return $tag;
	}
	/**
	 * @param int[] $itemsIds
	 */
	public function getByItemsIds(array $itemsIds): array
	{
		$connection = $this->dbConnection->getConnection();
		$itemsIdsString = implode(',', $itemsIds);
		$tags = [];

		$result = mysqli_query($connection, "
		SELECT it.ITEM_ID, t.ID, t.TITLE
		FROM N_ONE_TAGS t 
		JOIN N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
		WHERE it.ITEM_ID IN ({$itemsIdsString});
	");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$tags[$row['ITEM_ID']][] = new Tag(
				$row['ID'],
				$row['TITLE'],
			);
		}
		return $tags;
	}
	public function add(Tag|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		$result = mysqli_query($connection, "
		INSERT INTO N_ONE_TAGS (ID, TITLE)
		VALUES (
			{$tagId},
			'{$title}'
		);");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		return true;
	}
	public function update(Tag|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		$result = mysqli_query($connection, "
		UPDATE N_ONE_ITEMS 
		SET TITLE = '{$title}'
		WHERE ID = {$tagId}");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		return true;
	}
}