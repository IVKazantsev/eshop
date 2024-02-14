<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Image;
use RuntimeException;

class ImageRepository extends Repository
{

	public function getList(array $filter = null): array
	{
		$images = [];
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
		SELECT id, item_id, height, width, is_main, type, extension
		FROM N_ONE_IMAGES
		WHERE item_id IN (" . implode(',', $filter) . ");
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$images[$row['item_id']][] = new Image(
				$row['id'],
				$row['item_id'],
				$row['is_main'],
				$row['type'],
				$row['height'],
				$row['width'],
				$row['extension'],
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

		$result = mysqli_query(
			$connection,
			"
		SELECT id, item_id, height, width, is_main, type, extension
		FROM N_ONE_IMAGES 
		WHERE id = $id;
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		$image = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$image = new Image(
				$row['id'],
				$row['item_id'],
				$row['is_main'],
				$row['type'],
				$row['height'],
				$row['width'],
				$row['extension'],
			);
		}

		if ($image === null)
		{
			throw new RuntimeException("Item with id $id not found");
		}

		return $image;
	}

	public function add(Image|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$itemId = $entity->getItemId();
		$height = $entity->getHeight();
		$width = $entity->getWidth();
		$isMain = $entity->isMain();
		$type = $entity->getType();
		$extension = mysqli_real_escape_string($connection, $entity->getExtension());

		$result = mysqli_query(
			$connection,
			"
		INSERT INTO N_ONE_IMAGES (ITEM_ID, HEIGHT, WIDTH, IS_MAIN, TYPE, EXTENSION) 
		VALUES (
			$itemId,
			$height,
			$width,
			$isMain,
			{$type},
			'$extension'
		);"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return mysqli_insert_id($connection);
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
		$extension = mysqli_real_escape_string($connection, $entity->getExtension());

		$result = mysqli_query(
			$connection,
			"
		UPDATE N_ONE_IMAGES 
		SET 
			ITEM_ID = $itemId,
			HEIGHT = $height,
			WIDTH = $width,
			IS_MAIN = $isMain,
			TYPE = {$type},
			EXTENSION = '$extension'
		where ID = $imageId;
		"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
}