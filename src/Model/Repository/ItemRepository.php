<?php

namespace N_ONE\App\Model\Repository;

use Exception;
use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\App\Model\Tag;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\DbConnector\DbConnector;

class ItemRepository extends Repository
{
	private DbConnector $dbConnection;

	public function __construct(DbConnector $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		// $currentLimit = Configurator::option('NUM_OF_ITEMS_PER_PAGE');
		// $offset = calculateCurrentOffset($currentPageNumber);
		// $whereQueryBlock = getWhereQueryBlock($genre, $title, $connection);
		$items = [];

		$result = mysqli_query($connection, "
		SELECT i.ID, i.TITLE, i.IS_ACTIVE, i.PRICE, i.DESCRIPTION
		FROM N_ONE_ITEMS i;
	");

		if (!$result)
		{
			throw new Exception(mysqli_connect_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$items[] = new Item(
				$row['ID'],
				$row['TITLE'],
				$row['IS_ACTIVE'],
				$row['PRICE'],
				$row['DESCRIPTION'],
				[],
			);
		}

		if (empty($items))
		{
			throw new Exception("Items not found");
		}

		$itemsIds = implode(',', array_map(function($item) {return $item->getId();}, $items));

		$tags = $this->getTagsByItemsIds($itemsIds);

		foreach ($items as &$item)
		{
			$item->setTags($tags[$item->getId()] ?? []);
		}

		return $items;
	}

	private function getTagsByItemsIds(string $itemsIds): array
	{
		$connection = $this->dbConnection->getConnection();
		$tags = [];

		$result = mysqli_query($connection, "
		SELECT it.ITEM_ID, t.ID, t.TITLE
		FROM N_ONE_TAGS t 
		JOIN N_ONE_ITEMS_TAGS it on t.ID = it.TAG_ID
		WHERE it.ITEM_ID IN ({$itemsIds});
	");

		if (!$result)
		{
			throw new Exception(mysqli_connect_error($connection));
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

	public function getById(int $id): Item
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query($connection, "
		SELECT i.ID, i.TITLE, i.IS_ACTIVE, i.PRICE, i.DESCRIPTION
		FROM N_ONE_ITEMS i
		WHERE i.ID = {$id};
		");

		if (!$result)
		{
			throw new Exception(mysqli_connect_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$item = new Item(
				$row['ID'],
				$row['TITLE'],
				$row['IS_ACTIVE'],
				$row['PRICE'],
				$row['DESCRIPTION'],
				[],
			);
		}

		if (empty($item))
		{
			throw new Exception("Item with id {$id} not found");
		}

		$tags = $this->getTagsByItemsIds($item->getId());
		$item->setTags($tags[$item->getId()] ?? []);

		return $item;
	}

	public function add(Item|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$itemId = $entity->getId();
		$title = mysqli_real_escape_string($connection, $entity->getTitle());
		$isActive = $entity->isActive() ? 1 : 0;
		$price = $entity->getPrice();
		$description = mysqli_real_escape_string($connection, $entity->getDescription());
		$sortOrder = $entity->getSortOrder();
		$tags = $entity->getTags();

		$result = mysqli_query($connection, "
		INSERT INTO N_ONE_ITEMS (ID, TITLE, IS_ACTIVE, PRICE, DESCRIPTION, SORT_ORDER) 
		VALUES (
			{$itemId},
			'{$title}',
			{$isActive},
			{$price},
			'{$description}',
			{$sortOrder}
		);");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		$itemTags = "";

		foreach ($tags as $tag)
		{
			$itemTags = $itemTags . '(' . $itemId . ', ' . $tag->getId() . '),';
		}
		$itemTags = substr($itemTags, 0, -1);

		$result = mysqli_query($connection, "
		INSERT INTO N_ONE_ITEMS_TAGS (ITEM_ID, TAG_ID) VALUES " . $itemTags . ";");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
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

		$result = mysqli_query($connection, "
		UPDATE N_ONE_ITEMS 
		SET TITLE = '{$title}', 
			IS_ACTIVE = {$isActive}, 
			PRICE = {$price}, 
			DESCRIPTION = '{$description}', 
			SORT_ORDER = {$sortOrder}
		WHERE ID = {$itemId}");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		$result = mysqli_query($connection, "
		DELETE FROM N_ONE_ITEMS_TAGS WHERE ITEM_ID = {$itemId}");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		$itemTags = "";

		foreach ($tags as $tag)
		{
			$itemTags = $itemTags . '(' . $itemId . ', ' . $tag->getId() . '),';
		}
		$itemTags = substr($itemTags, 0, -1);

		$sql = "INSERT INTO N_ONE_ITEMS_TAGS (ITEM_ID, TAG_ID) VALUES " . $itemTags . ";";
		$result = mysqli_query($connection, $sql);

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		return true;
	}
}