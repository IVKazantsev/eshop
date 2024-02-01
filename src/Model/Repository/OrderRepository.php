<?php

namespace N_ONE\App\Model\Repository;

use Exception;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;

class OrderRepository extends Repository
{
	private DbConnector $dbConnection;

	public function __construct(DbConnector $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getList(array $filter): array
	{
		// TODO: Implement getList() method.
	}

	public function getById(int $id): Order
	{
		// TODO: Implement getById() method.
	}

	public function add(Order|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$orderId = $entity->getId();
		$userId = $entity->getUser()->getId();
		$itemId = $entity->getItem()->getId();
		$statusId = $entity->getStatusId();
		$price = $entity->getPrice();

		$result = mysqli_query($connection, "
		INSERT INTO N_ONE_ORDERS (ID, USER_ID, ITEM_ID, STATUS_ID, PRICE) 
		VALUES (
			{$orderId},
			{$userId},
			{$itemId},
			{$statusId},
			{$price}
		);");

		if (!$result)
		{
			throw new Exception(mysqli_error($connection));
		}

		return true;
	}

	public function update(Order|Entity $entity): bool
	{
		return true;
	}
}