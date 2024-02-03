<?php

namespace N_ONE\App\Model\Repository;

use Exception;
use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Image;
use N_ONE\Core\DbConnector\DbConnector;

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
			throw new Exception(mysqli_connect_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$images[$row['item_id']][] = new Image(
				$row['id'],
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
			throw new Exception("Items not found");
		}

		return $images;
	}

	public function getById(int $id): Entity
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query($connection, "
		SELECT id, item_id, height, width, is_main, type, path
		FROM N_ONE_IMAGES 
		WHERE id = {$id};
		");

		if (!$result)
		{
			throw new Exception(mysqli_connect_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$image = new Image(
				$row['id'],
				$row['item_id'],
				$row['path'],
				$row['is_main'],
				$row['type'],
				$row['height'],
				$row['width'],
			);
		}

		if (empty($image))
		{
			throw new Exception("Item with id {$id} not found");
		}

		return $image;
	}

	public function add(Entity $entity): bool
	{
		// TODO: Implement add() method.
	}

	public function update(Entity $entity): bool
	{
		// TODO: Implement update() method.
	}
}