<?php

namespace N_ONE\App\Model\Repository;

use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Exceptions\DatabaseException;

class ItemRepository extends Repository
{
	public function __construct(
		DbConnector                          $dbConnection,
		private readonly TagRepository       $tagRepository,
		private readonly ImageRepository     $imageRepository,
		private readonly AttributeRepository $attributeRepository
	)
	{
		parent::__construct($dbConnection);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getById(int $id): ?Item
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
			SELECT i.ID, i.TITLE, i.IS_ACTIVE, i.PRICE, i.DESCRIPTION, i.SORT_ORDER
			FROM N_ONE_ITEMS i
			WHERE i.ID = $id;
			"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$item = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$item = new Item(
				$row['ID'],
				$row['TITLE'],
				$row['PRICE'],
				$row['DESCRIPTION'],
				$row['SORT_ORDER'],
				$this->tagRepository->getByItemsIds([$row['ID']])[$row['ID']],
				$this->attributeRepository->getByItemsIds([$row['ID']])[$row['ID']],
				$this->imageRepository->getList([$row['ID']]) [$row['ID']] ?? []
			);

			$item->setId($id);
		}

		return $item;
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
		$fulltext = $filter['title, description'] ?? null;
		$tags = $filter['tags'] ?? null;
		$attributes = $filter['attributes'] ?? null;
		$sortOrder = $filter['sortOrder'] ?? null;

		$whereQueryBlock = $this->getWhereQueryBlock($tags, $fulltext, $attributes, $isActive, $sortOrder, $connection);

		$result = mysqli_query(
			$connection,
			"SELECT i.ID, i.TITLE, i.IS_ACTIVE, i.PRICE, i.DESCRIPTION, i.SORT_ORDER
			FROM N_ONE_ITEMS i
			$whereQueryBlock
			LIMIT $currentLimit OFFSET $offset;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$items = $this->getItemsFromResult($result);

		if (empty($items))
		{
			return $items;
		}

		return $this->fillItemsWithOtherEntities($items);
	}

	private function getWhereQueryBlock(
		?array  $tags,
		?string $fulltext,
		?array  $attributes,
		?int    $isActive,
		?array  $sortOrder,
		mysqli  $connection
	): string
	{
		$whereQueryBlock = "";
		$conditions = [];
		if ($sortOrder === null)
		{
			$sortQueryBlock = "ORDER BY i.SORT_ORDER DESC";
		}
		elseif ($sortOrder['column'] === 'PRICE')
		{
			$sortQueryBlock = "ORDER BY i.PRICE {$sortOrder['direction']}";
		}
		elseif (is_int($sortOrder['column']))
		{
			$sortQueryBlock = "ORDER BY ia.VALUE {$sortOrder['direction']}";
			$conditions[] = "ia.ATTRIBUTE_ID = {$sortOrder['column']}";
			$whereQueryBlock .= " JOIN N_ONE_ITEMS_ATTRIBUTES ia ON ia.ITEM_ID = i.ID";
		}

		$conditions[] = "i.IS_ACTIVE = $isActive";
		if ($fulltext !== null && $fulltext !== "")
		{
			$itemFulltext = mysqli_real_escape_string($connection, $fulltext);
			$conditions[] = "MATCH (title,description) AGAINST ('$itemFulltext*' IN BOOLEAN MODE)";
			$sortQueryBlock = "ORDER BY MATCH (title,description) AGAINST ('$itemFulltext*' IN BOOLEAN MODE) DESC";
		}

		foreach ($attributes as $key => $attribute)
		{
			$attributeId = $key;
			$from = $attribute['from'];
			$to = $attribute['to'];

			$conditions[] = "EXISTS
				(SELECT 1 FROM N_ONE_ITEMS_ATTRIBUTES ia$attributeId
				WHERE
				ia$attributeId.ITEM_ID = i.ID AND
				ia$attributeId.ATTRIBUTE_ID = $attributeId AND
				ia$attributeId.VALUE BETWEEN $from AND $to)";
		}

		foreach ($tags as $parentId => $tagIds)
		{
			$tagIdsString = implode(',', $tagIds);

			$conditions[] = "EXISTS 
				(SELECT 1 FROM N_ONE_ITEMS_TAGS it 
				JOIN N_ONE_TAGS t on t.ID = it.TAG_ID
				WHERE it.ITEM_ID = i.ID 
				AND t.PARENT_ID = $parentId 
				AND it.TAG_ID IN ($tagIdsString))";
		}

		$whereQueryBlock .= " WHERE " . implode(' AND ', $conditions);
		$whereQueryBlock .= " $sortQueryBlock" ?? "";

		return $whereQueryBlock;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByIds(array $ids): array
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
			SELECT i.ID, i.TITLE, i.IS_ACTIVE, i.PRICE, i.DESCRIPTION, i.SORT_ORDER
			FROM N_ONE_ITEMS i
			WHERE i.ID IN (" . implode(',', $ids) . ");
			"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$items = $this->getItemsFromResult($result);

		if (empty($items))
		{
			return $items;
		}

		return $this->fillItemsWithOtherEntities($items);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function add(Item|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$price = $entity->getPrice();
		$description = mysqli_real_escape_string($connection, $entity->getDescription());
		$sortOrder = $entity->getSortOrder();
		$tags = $entity->getTags();
		$attributes = $entity->getAttributes();

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_ITEMS (TITLE,  PRICE, DESCRIPTION, SORT_ORDER) 
			VALUES (
				'$title',
				
				$price,
				'$description',
				$sortOrder
				);"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$itemId = mysqli_insert_id($connection);

		if (!empty($tags))
		{
			$this->updateItemTags($connection, $itemId, $tags);
		}
		if (!empty($attributes))
		{
			$this->updateItemAttributes($connection, $itemId, $attributes);
		}

		return $itemId;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function update(Item|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$itemId = $entity->getId();
		$sortOrder = $entity->getSortOrder();
		$tags = $entity->getTags();
		$attributes = $entity->getAttributes();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$price = mysqli_real_escape_string($connection, $entity->getPrice());
		$description = mysqli_real_escape_string($connection, $entity->getDescription());

		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_ITEMS 
			SET 
				TITLE = '$title', 
				PRICE = $price, 
				DESCRIPTION = '$description', 
				SORT_ORDER = {$sortOrder}
			WHERE ID = $itemId"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		if (!empty($tags))
		{
			$this->updateItemTags($connection, $itemId, $tags);
		}
		if (!empty($attributes))
		{
			$this->updateItemAttributes($connection, $itemId, $attributes);
		}

		return true;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	private function updateItemTags(bool|mysqli $connection, int $itemId, array $tags): void
	{
		$result = mysqli_query(
			$connection,
			"
			DELETE FROM N_ONE_ITEMS_TAGS 
			WHERE ITEM_ID = $itemId"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$this->addItemTags($connection, $itemId, $tags);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	private function updateItemAttributes(bool|mysqli $connection, int $itemId, array $attributes): void
	{
		$result = mysqli_query(
			$connection,
			"
			DELETE FROM N_ONE_ITEMS_ATTRIBUTES 
			WHERE ITEM_ID = $itemId"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$this->addItemAttributes($connection, $itemId, $attributes);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	private function addItemTags(bool|mysqli $connection, int $itemId, array $tags): void
	{
		$itemTags = "";
		foreach ($tags as $tag)
		{
			$itemTags .= '(' . $itemId . ', ' . $tag . '),';
		}

		$itemTags = substr($itemTags, 0, -1);

		$result = mysqli_query(
			$connection,
			"INSERT INTO N_ONE_ITEMS_TAGS (ITEM_ID, TAG_ID) 
			VALUES " . $itemTags . ";"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	private function addItemAttributes(bool|mysqli $connection, int $itemId, array $attributes): void
	{
		$itemAttributes = "";
		foreach ($attributes as $attributeId => $attribute)
		{
			if (!trim($attribute))
			{
				$attribute = 'null';
			}
			$itemAttributes .= '(' . $itemId . ', ' . $attributeId . ', ' . $attribute . '),';
		}
		$itemAttributes = substr($itemAttributes, 0, -1);

		$result = mysqli_query(
			$connection,
			"INSERT INTO N_ONE_ITEMS_ATTRIBUTES (ITEM_ID, ATTRIBUTE_ID, VALUE) 
			VALUES " . $itemAttributes . ";"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
	}

	/**
	 * @param Item[] $items
	 *
	 * @return Item[]
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function fillItemsWithOtherEntities(array $items): array
	{
		$itemsIds = array_map(static function($item) {
			return $item->getId();
		}, $items);

		$tags = $this->tagRepository->getByItemsIds($itemsIds);
		$attributes = $this->attributeRepository->getByItemsIds($itemsIds);
		$images = $this->imageRepository->getList($itemsIds);

		foreach ($items as $item)
		{
			$item->setTags($tags[$item->getId()] ?? []);
			$item->setAttributes($attributes[$item->getId()] ?? []);
			$item->setImages($images[$item->getId()] ?? []);
		}

		return $items;
	}

	/**
	 * @return Item[]
	 */
	public function getItemsFromResult(mysqli_result $result): array
	{
		$items = [];
		while ($row = mysqli_fetch_assoc($result))
		{
			$items[] = new Item(
				$row['ID'], $row['TITLE'], $row['PRICE'], $row['DESCRIPTION'], $row['SORT_ORDER'], [], [], []
			);
		}

		return $items;
	}
}