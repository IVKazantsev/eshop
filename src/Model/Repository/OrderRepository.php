<?php

namespace N_ONE\App\Model\Repository;

use mysqli_sql_exception;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;

class OrderRepository extends Repository
{
	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$offset = $this->calculateOffset($filter['pageNumber']);
		$whereQueryBlock = $this->getWhereQueryBlock($filter['isActive'] ?? 1);

		$result = mysqli_query(
			$connection,
			"
			SELECT o.ID, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE, o.NUMBER
			FROM N_ONE_ORDERS o
			JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID
			$whereQueryBlock
			LIMIT $this->currentLimit OFFSET $offset;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$orders = [];
		while ($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['ID'],
				$row['USER_ID'],
				$row['ITEM_ID'],
				$row['STATUS_ID'],
				$row['TITLE'],
				$row['PRICE'],
				$row['NUMBER']
			);

			$orders[] = $order;
		}

		return $orders;
	}

	private function getWhereQueryBlock(int $isActive): string
	{
		return "WHERE o.IS_ACTIVE = $isActive";
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getById(int $id, bool $isPublic = false): ?Order
	{
		$connection = $this->dbConnection->getConnection();
		if ($isPublic)
		{
			$isActive = "(1)";
		}
		else
		{
			$isActive = "(1, 0)";
		}
		$result = mysqli_query(
			$connection,
			"
			SELECT o.ID, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE, o.NUMBER
			FROM N_ONE_ORDERS o
			JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID
			WHERE o.ID = $id and o.IS_ACTIVE in $isActive;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$order = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['ID'], $row['USER_ID'], $row['ITEM_ID'], $row['STATUS_ID'], $row['TITLE'], $row['PRICE'], $row['NUMBER']
			);
		}

		return $order;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
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
	 * @throws mysqli_sql_exception
	 */
	public function add(Order|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$userId = $entity->getUserId();
		$itemId = $entity->getItemId();
		$statusId = $entity->getStatusId();
		$price = $entity->getPrice();
		$orderNumber = $entity->getNumber();

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_ORDERS (USER_ID, ITEM_ID, STATUS_ID, PRICE, NUMBER) 
			VALUES (
				$userId,
				$itemId,
				$statusId,
				$price,
				'$orderNumber'
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
	 * @throws mysqli_sql_exception
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

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByNumber(string $number, bool $isPublic = false): ?Order
	{
		$connection = $this->dbConnection->getConnection();
		$number = mysqli_real_escape_string($connection, $number);
		if ($isPublic)
		{
			$isActive = "(1)";
		}
		else
		{
			$isActive = "(1, 0)";
		}
		$result = mysqli_query(
			$connection,
			"
			SELECT o.ID, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE
			FROM N_ONE_ORDERS o
			JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID
			WHERE o.NUMBER = '$number' and o.IS_ACTIVE in $isActive;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$order = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['ID'], $row['USER_ID'], $row['ITEM_ID'], $row['STATUS_ID'], $row['TITLE'], $row['PRICE'], $number
			);
		}

		return $order;
	}
}