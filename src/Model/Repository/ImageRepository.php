<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Image;
use N_ONE\Core\Exceptions\DatabaseException;

class ImageRepository extends Repository
{

	/**
	 * @throws DatabaseException
	 */
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
			throw new DatabaseException(mysqli_error($connection));
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

		return $images;
	}

	/**
	 * @throws DatabaseException
	 */
	public function getById(int $id): Image|null
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
			throw new DatabaseException(mysqli_error($connection));
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

		return $image;
	}

	/**
	 * @throws DatabaseException
	 */
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
			$type,
			'$extension'
		);"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return mysqli_insert_id($connection);
	}

	/**
	 * @throws DatabaseException
	 */
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
			TYPE = $type,
			EXTENSION = '$extension'
		where ID = $imageId;
		"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}
}