<?php

namespace N_ONE\App\Model\Repository;

use mysqli_sql_exception;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use RuntimeException;

class TagRepository extends Repository
{
	/**
	 * @throws DatabaseException
	 */
	public function getAll(): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];
		$whereQueryBlock = $this->getWhereQueryBlock(1);

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE, t.PARENT_ID 
			FROM N_ONE_TAGS t
			$whereQueryBlock"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID']
			);
		}

		return $tags;
	}
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
		$tags = [];

		$whereQueryBlock = $this->getWhereQueryBlock($isActive);

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE, t.PARENT_ID 
			FROM N_ONE_TAGS t
			$whereQueryBlock
			LIMIT $currentLimit OFFSET $offset;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID']
			);
		}

		return $tags;
	}

	private function getWhereQueryBlock(int $isActive): string
	{
		return "WHERE t.IS_ACTIVE = $isActive";
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getParentTags(): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE 
			FROM N_ONE_TAGS t
			WHERE t.PARENT_ID IS NULL AND t.IS_ACTIVE != 0;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
				null,
			);
		}

		if (empty($tags))
		{
			throw new RuntimeException("Entities not found");
		}

		return $tags;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getById(int $id): Tag
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE, t.PARENT_ID
			FROM N_ONE_TAGS t
			LEFT JOIN N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
			WHERE t.ID = $id ;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$tag = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$tag = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID']
			);
		}

		return $tag;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByTitle(string $title): Tag|null
	{
		$connection = $this->dbConnection->getConnection();
		$title = mysqli_real_escape_string($connection, $title);

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE, t.PARENT_ID
			FROM N_ONE_TAGS t
			WHERE t.TITLE = '$title'"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$tag = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$tag = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID']
			);
		}

		return $tag;
	}

	/**
	 * @
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByItemsIds(array $itemsIds): array
	{
		$connection = $this->dbConnection->getConnection();
		$itemsIdsString = implode(',', $itemsIds);
		$tags = [];

		$result = mysqli_query(
			$connection,
			"
			SELECT it.ITEM_ID, t.ID, t.TITLE, t.PARENT_ID
			FROM N_ONE_TAGS t 
			JOIN N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
			WHERE it.ITEM_ID IN ($itemsIdsString)
			AND t.IS_ACTIVE = 1;
	"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[$row['ITEM_ID']][] = new Tag(
				$row['ID'],
				$row['TITLE'],
				$row['PARENT_ID'],
			);
		}

		if (empty($tags))
		{
			foreach ($itemsIds as $itemsId)
			{
				$tags[$itemsId] = [];
			}
		}

		return $tags;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function add(Tag|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$parentId = $entity->getParentId();

		if ($parentId)
		{
			$result = mysqli_query(
				$connection,
				"
				INSERT INTO N_ONE_TAGS (TITLE, PARENT_ID)
				VALUES (
					'$title',
					$parentId
				);"
			);
			$countOfParentTags = $this->checkIfParentHasTags($parentId);

			if ($countOfParentTags === 1)
			{
				$this->toggleParentTagIsActive($parentId, true);
			}

		}
		else
		{
			$result = mysqli_query(
				$connection,
				"
				INSERT INTO N_ONE_TAGS (TITLE)
				VALUES ('$title');"
			);
		}

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return mysqli_insert_id($connection);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function update(Tag|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$oldParentId = $this->getParentById($tagId);
		$title = mysqli_real_escape_string($connection, $entity->getTitle());

		if ($oldParentId === null)
		{
			$result = mysqli_query(
				$connection,
				"
				UPDATE N_ONE_TAGS 
				SET 
				TITLE = '$title',
				PARENT_ID = null
				WHERE ID = $tagId"
			);
		}
		else
		{
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
			$this->parentUpdate($parentId, $oldParentId);
		}

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
	public function getByParentId(int $id): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE
			FROM N_ONE_TAGS t
			WHERE t.PARENT_ID = $id
			AND t.IS_ACTIVE = 1;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
				$id,
			);
		}

		if (empty($tags))
		{
			throw new RuntimeException("Entities not found");
		}

		return $tags;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	private function toggleParentTagIsActive(int $parentId, bool $toggle): void
	{
		$connection = $this->dbConnection->getConnection();
		$isActive = (int)$toggle;
		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_TAGS 
			SET IS_ACTIVE = $isActive
			WHERE ID = $parentId"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
	}

	private function checkIfParentHasTags(int $parentId): int|string
	{
		$connection = $this->dbConnection->getConnection();
		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID
			FROM N_ONE_TAGS t
			WHERE t.PARENT_ID = '$parentId' and t.IS_ACTIVE = 1 ;"
		);

		return mysqli_num_rows($result);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	private function getParentById(int $id): ?int
	{
		$connection = $this->dbConnection->getConnection();
		$result = mysqli_query(
			$connection,
			"
			SELECT t.PARENT_ID
			FROM N_ONE_TAGS t
			WHERE t.ID = $id ;"
		);
		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return mysqli_fetch_assoc($result)['PARENT_ID'];
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getAllParentTags(): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];

		$result = mysqli_query(
			$connection,
			"
			SELECT t.ID, t.TITLE 
			FROM N_ONE_TAGS t
			WHERE t.PARENT_ID IS NULL;
		"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$tags[] = new Tag(
				$row['ID'],
				$row['TITLE'],
				null,
			);
		}

		if (empty($tags))
		{
			throw new RuntimeException("Entities not found");
		}

		return $tags;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function parentUpdate($parentId, $oldParentId): void
	{
		$countOfNewParentTags = $this->checkIfParentHasTags($parentId);
		$countOfOldParentTags = $this->checkIfParentHasTags($oldParentId);

		if ($countOfOldParentTags === 0)
		{
			$this->toggleParentTagIsActive($oldParentId, false);
		}
		elseif ($countOfOldParentTags === 1)
		{
			$this->toggleParentTagIsActive($oldParentId, true);
		}
		if ($countOfNewParentTags === 1)
		{
			$this->toggleParentTagIsActive($parentId, true);
		}
		elseif ($countOfNewParentTags === 0)
		{
			$this->toggleParentTagIsActive($parentId, false);
		}
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function changeActive(string $entities, int $entityId, int $isActive): bool
	{
		$connection = $this->dbConnection->getConnection();
		$entities = mysqli_real_escape_string($connection, $entities);
		$tag = $this->getById($entityId);

		if ($tag->getParentId()) //если дочерний тег
		{
			$result = mysqli_query(
				$connection,
				"
				UPDATE N_ONE_$entities 
				SET IS_ACTIVE = $isActive
				WHERE ID = $entityId"
			);
			//TODO удаление из ITEM_TAGS

			// mysqli_query(
			// 	$connection,
			// 	"
			// 	DELETE FROM N_ONE_ITEMS_TAGS
			// 	WHERE TAG_ID = $entityId; "
			// );

			$countOfParentTags = $this->checkIfParentHasTags($tag->getParentId());
			if ($countOfParentTags === 0)
			{
				$this->toggleParentTagIsActive($tag->getParentId(), false);
			}
			elseif ($countOfParentTags === 1)
			{
				$this->toggleParentTagIsActive($tag->getParentId(), true);
			}
		}
		else //если родительский тег
		{
			$result = mysqli_query(
				$connection,
				"
				UPDATE N_ONE_$entities 
				SET IS_ACTIVE = $isActive
				WHERE ID = $entityId or PARENT_ID = $entityId"
			);
		}

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}
}