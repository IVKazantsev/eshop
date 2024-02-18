<?php

namespace N_ONE\App\Model\Repository;

use mysqli;
use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\TagService;
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

		while ($row = mysqli_fetch_assoc($result))
		{
			$item = new Item(
				$row['ID'],
				$row['TITLE'],
				$row['IS_ACTIVE'],
				$row['PRICE'],
				$row['DESCRIPTION'],
				$row['SORT_ORDER'],
				$this->tagRepository->getByItemsIds([$row['ID']])[$row['ID']],
				$this->attributeRepository->getByItemsIds([$row['ID']])[$row['ID']],
				$this->imageRepository->getList([$row['ID']]) [$row['ID']] ?? []
			);

			$item->setId($id);
		}

		if (empty($item))
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return $item;
	}

	/**
	 * @throws DatabaseException
	 */
	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$numItemsPerPage = Configurator::option('NUM_OF_ITEMS_PER_PAGE');
		$currentLimit = $numItemsPerPage + 1;
		$offset = ($filter['pageNumber'] ?? 0) * $numItemsPerPage;
		$tag = $filter['tag'] ?? null;
		$title = $filter['title'] ?? null;
		$range = $filter['range'] ?? null;

		$whereQueryBlock = $this->getWhereQueryBlock($tag, $title, $range, $connection);
		$items = [];

		$result = mysqli_query(
			$connection,
			"SELECT i.ID, i.TITLE, i.IS_ACTIVE, i.PRICE, i.DESCRIPTION, i.SORT_ORDER
			FROM N_ONE_ITEMS i
			$whereQueryBlock
			LIMIT {$currentLimit} OFFSET {$offset};"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$items[] = new Item(
				$row['ID'],
				$row['TITLE'],
				$row['IS_ACTIVE'],
				$row['PRICE'],
				$row['DESCRIPTION'],
				$row['SORT_ORDER'],
				[],
				[],
				[]
			);

			// $items[count($items) - 1]->setId($row['ID']);
		}

		if (empty($items))
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$itemsIds = array_map(function($item) {
			return $item->getId();
		}, $items);

		$tags = $this->tagRepository->getByItemsIds($itemsIds);
		$attributes = $this->attributeRepository->getByItemsIds($itemsIds);
		$images = $this->imageRepository->getList($itemsIds);

		foreach ($items as &$item)
		{
			$item->setTags($tags[$item->getId()] ?? []);
			$item->setAttributes($attributes[$item->getId()] ?? []);
			$item->setImages($images[$item->getId()] ?? []);
		}

		return $items;
	}

	private function getWhereQueryBlock(?string $tag, ?string $title, ?string $range, mysqli $connection): string
	{

		$whereQueryBlock = "WHERE i.IS_ACTIVE = 1";
		if ($range !== null)
		{
			[$attributeId, $from, $to] = TagService::reformatRangeTag($range);
			$whereQueryBlock = "
			JOIN N_ONE_ITEMS_ATTRIBUTES ia on i.ID = ia.ITEM_ID
			WHERE ia.ATTRIBUTE_ID = $attributeId and (ia.VALUE BETWEEN $from and $to) and i.IS_ACTIVE = 1
		";
		}
		elseif ($tag !== null && $title !== null)
		{
			$tagTitle = mysqli_real_escape_string($connection, $tag);
			$itemTitle = mysqli_real_escape_string($connection, $title);
			$whereQueryBlock = "
			JOIN N_ONE_ITEMS_TAGS it on i.ID = it.ITEM_ID
			JOIN N_ONE_TAGS t on it.TAG_ID = t.ID
			WHERE t.TITLE = '$tagTitle'  and i.TITLE LIKE '%$itemTitle%' and i.IS_ACTIVE = 1
		";
		}
		elseif ($tag !== null)
		{
			$tagTitle = mysqli_real_escape_string($connection, $tag);
			$whereQueryBlock = "
			JOIN N_ONE_ITEMS_TAGS it on i.ID = it.ITEM_ID
			JOIN N_ONE_TAGS t on it.TAG_ID = t.ID
			WHERE t.TITLE = '$tagTitle' and i.IS_ACTIVE = 1
		";
		}
		elseif ($title !== null)
		{
			$itemTitle = mysqli_real_escape_string($connection, $title);
			$whereQueryBlock = "
			WHERE i.TITLE LIKE '%$itemTitle%' and i.IS_ACTIVE = 1
		";
		}

		return $whereQueryBlock;
	}

	public function getByIds(array $ids): array
	{
		$connection = $this->dbConnection->getConnection();
		$items = [];

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

		while ($row = mysqli_fetch_assoc($result))
		{
			$items[] = new Item(
				$row['ID'],
				$row['TITLE'],
				$row['IS_ACTIVE'],
				$row['PRICE'],
				$row['DESCRIPTION'],
				$row['SORT_ORDER'],
				[],
				[],
				[]
			);
		}

		if (empty($items))
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$itemsIds = array_map(function($item) {
			return $item->getId();
		}, $items);

		$tags = $this->tagRepository->getByItemsIds($itemsIds);
		$attributes = $this->attributeRepository->getByItemsIds($itemsIds);
		$images = $this->imageRepository->getList($itemsIds);

		foreach ($items as &$item)
		{
			$item->setTags($tags[$item->getId()] ?? []);
			$item->setAttributes($attributes[$item->getId()] ?? []);
			$item->setImages($images[$item->getId()] ?? []);
		}

		return $items;
	}

	public function add(Item|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$itemId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$isActive = $entity->isActive() ? 1 : 0;
		$price = $entity->getPrice();
		$description = mysqli_real_escape_string($connection, $entity->getDescription());
		$sortOrder = $entity->getSortOrder();
		$tags = $entity->getTags();

		$result = mysqli_query(
			$connection,
			"
		INSERT INTO N_ONE_ITEMS (ID, TITLE, IS_ACTIVE, PRICE, DESCRIPTION, SORT_ORDER) 
		VALUES (
			{$itemId},
			'{$title}',
			{$isActive},
			{$price},
			'{$description}',
			{$sortOrder}
		);"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$itemTags = "";

		foreach ($tags as $tag)
		{
			$itemTags = $itemTags . '(' . $itemId . ', ' . $tag->getId() . '),';
		}
		$itemTags = substr($itemTags, 0, -1);

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_ITEMS_TAGS (ITEM_ID, TAG_ID) VALUES " . $itemTags . ";"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}

	public function update(Item|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$itemId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$isActive = $entity->isActive() ? 1 : 0;
		$price = mysqli_real_escape_string($connection, $entity->getPrice());
		$description = mysqli_real_escape_string($connection, $entity->getDescription());
		$sortOrder = $entity->getSortOrder();
		$tags = $entity->getTags();
		$attributes = $entity->getAttributes();
		$result = mysqli_query(
			$connection,
			"
		UPDATE N_ONE_ITEMS 
		SET TITLE = '{$title}', 
			IS_ACTIVE = {$isActive}, 
			PRICE = {$price}, 
			DESCRIPTION = '{$description}', 
			SORT_ORDER = {$sortOrder}
		WHERE ID = {$itemId}"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$this->updateItemTags($connection, $itemId, $tags);
		$this->updateItemAttributes($connection, $itemId, $attributes);

		return true;
	}

	private function updateItemTags(bool|mysqli $connection, int $itemId, array $tags): void
	{
		$result = mysqli_query(
			$connection,
			"
			DELETE FROM N_ONE_ITEMS_TAGS WHERE ITEM_ID = {$itemId}"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
		// var_dump($tags);
		$itemTags = "";
		foreach ($tags as $tag)
		{
			// var_dump($tag);
			$itemTags = $itemTags . '(' . $itemId . ', ' . $tag . '),';
		}
		// exit();
		$itemTags = substr($itemTags, 0, -1);

		$sql = "INSERT INTO N_ONE_ITEMS_TAGS (ITEM_ID, TAG_ID) VALUES " . $itemTags . ";";
		$result = mysqli_query($connection, $sql);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
	}

	private function updateItemAttributes(bool|mysqli $connection, int $itemId, array $attributes): void
	{
		$result = mysqli_query(
			$connection,
			"
			DELETE FROM N_ONE_ITEMS_ATTRIBUTES WHERE ITEM_ID = {$itemId}"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
		$itemAttributes = "";
		foreach ($attributes as $attributeId => $attribute)
		{
			if (!trim($attribute))
			{
				$attribute = 'null';
			}
			$itemAttributes = $itemAttributes . '(' . $itemId . ', ' . $attributeId . ', ' . $attribute . '),';
		}
		$itemAttributes = substr($itemAttributes, 0, -1);
		var_dump($itemAttributes);
		$sql = "INSERT INTO N_ONE_ITEMS_ATTRIBUTES (ITEM_ID, ATTRIBUTE_ID, VALUE) VALUES " . $itemAttributes . ";";
		$result = mysqli_query($connection, $sql);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
	}
}