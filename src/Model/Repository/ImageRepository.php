<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Image;
use N_ONE\Core\DbConnector\DbConnector;
use RuntimeException;

class ImageRepository extends Repository
{

	private DbConnector $dbConnection;

	public function __construct(DbConnector $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getList(array $filter = null): array
	{
		$images = [];
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query($connection, "
		SELECT id, item_id, height, width, is_main, type, path
		FROM N_ONE_IMAGES
		WHERE item_id IN (" . implode(',', $filter) . ");
		");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$images[$row['item_id']][] = new Image(
				$row['item_id'],
				$row['path'],
				$row['is_main'],
				$row['type'],
				$row['height'],
				$row['width'],
			);
		}

		if (empty($images))
		{
			throw new RuntimeException("Items not found");
		}

		return $images;
	}

	public function getById(int $id): Image
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query($connection, "
		SELECT id, item_id, height, width, is_main, type, path
		FROM N_ONE_IMAGES 
		WHERE id = $id;
		");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		$image = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$image = new Image(
				$row['item_id'],
				$row['path'],
				$row['is_main'],
				$row['type'],
				$row['height'],
				$row['width'],
			);
		}

		if ($image === null)
		{
			throw new RuntimeException("Item with id $id not found");
		}

		return $image;
	}

	public function add(Image|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$imageId = $entity->getId();
		$itemId = $entity->getItemId();
		$height = $entity->getHeight();
		$width = $entity->getWidth();
		$isMain = $entity->isMain();
		$type = $entity->getType();

		$result = mysqli_query($connection, "
		INSERT INTO N_ONE_IMAGES (ID, ITEM_ID, HEIGHT, WIDTH, IS_MAIN, TYPE) 
		VALUES (
			$imageId,
			$itemId,
			$height,
			$width,
			$isMain,
			{$type}
		);");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}

	public function update(Image|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$imageId = $entity->getId();
		$itemId = $entity->getItemId();
		$height = $entity->getHeight();
		$width = $entity->getWidth();
		$isMain = $entity->isMain();
		$type = $entity->getType();

		$result = mysqli_query($connection, "
		UPDATE N_ONE_IMAGES 
		SET 
			ITEM_ID = $itemId,
			HEIGHT = $height,
			WIDTH = $width,
			IS_MAIN = $isMain,
			TYPE = {$type}
		where ID = $imageId;
		");


		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
}