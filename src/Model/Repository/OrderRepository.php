<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Order;
use N_ONE\App\Model\Entity;
use N_ONE\Core\Exceptions\DatabaseException;

class OrderRepository extends Repository
{

	/**
	 * @throws DatabaseException
	 */
	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$orders = [];

		$whereQueryBlock = $this->getWhereQueryBlock();

		$result = mysqli_query(
			$connection,
			"
			SELECT o.ID, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE 
			FROM N_ONE_ORDERS o
			JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID
			$whereQueryBlock;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['ID'],
				$row['USER_ID'],
				$row['ITEM_ID'],
				$row['STATUS_ID'],
				$row['TITLE'],
				$row['PRICE'],
			);

			$orders[] = $order;
		}

		return $orders;
	}

	private function getWhereQueryBlock(): string
	{
		$whereQueryBlock = "WHERE o.IS_ACTIVE = 1";

		return $whereQueryBlock;
	}

	/**
	 * @throws DatabaseException
	 */
	public function getById(int $id): Order|null
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
			SELECT o.ID, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE 
			FROM N_ONE_ORDERS o
			JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID
			WHERE o.ID = $id;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$order = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['ID'],
				$row['USER_ID'],
				$row['ITEM_ID'],
				$row['STATUS_ID'],
				$row['TITLE'],
				$row['PRICE'],
			);
		}

		return $order;
	}

	/**
	 * @throws DatabaseException
	 */
	public function getStatuses(): array
	{
		$connection = $this->dbConnection->getConnection();
		$result = mysqli_query(
			$connection,
			"
			SELECT ID, TITLE 
			FROM N_ONE_STATUSES s;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
		$statuses = [];
		while ($row = mysqli_fetch_assoc($result))
		{
			$statuses[(string)($row['ID'])] = $row['TITLE'];
		}

		return $statuses;
	}

	/**
	 * @throws DatabaseException
	 */
	public function add(Order|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$userId = $entity->getUserId();
		$itemId = $entity->getItemId();
		$statusId = $entity->getStatusId();
		$price = $entity->getPrice();

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_ORDERS (USER_ID, ITEM_ID, STATUS_ID, PRICE) 
			VALUES (
				$userId,
				$itemId,
				$statusId,
				$price
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
	public function update(Order|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$orderId = $entity->getId();
		$userId = $entity->getUserId();
		$itemId = $entity->getItemId();
		$statusId = $entity->getStatusId();
		$price = $entity->getPrice();

		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_ORDERS 
			SET 
				USER_ID = $userId,
				ITEM_ID = $itemId,
				STATUS_ID = $statusId,
				PRICE = $price
			WHERE ID = $orderId;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}
}