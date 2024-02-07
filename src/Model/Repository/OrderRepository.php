<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\Order;
use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;
use RuntimeException;

class OrderRepository extends Repository
{
	private DbConnector $dbConnection;
	private UserRepository $userRepository;
	private ItemRepository $itemRepository;

	public function __construct(
		DbConnector    $dbConnection,
		UserRepository $userRepository,
		ItemRepository $itemRepository
	)
	{
		$this->dbConnection = $dbConnection;
		$this->userRepository = $userRepository;
		$this->itemRepository = $itemRepository;
	}

	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		// $currentLimit = Configurator::option('NUM_OF_ITEMS_PER_PAGE');
		// $offset = calculateCurrentOffset($currentPageNumber);
		// $whereQueryBlock = getWhereQueryBlock($genre, $title, $connection);
		$orders = [];

		$result = mysqli_query(
			$connection,
			"
		SELECT o.ID, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE 
		FROM N_ONE_ORDERS o
		JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID;
	");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['USER_ID'], $row['ITEM_ID'], $row['STATUS_ID'], $row['TITLE'], $row['PRICE'],
			);
			$order->setId($row['ID']);
			$orders[] = $order;
		}

		if (empty($orders))
		{
			throw new RuntimeException("Items not found");
		}

		$itemsIds = array_map(static function($order) {return $order->getItemId();}, $orders);
		$usersIds = array_map(static function($user) {return $user->getUserId();}, $orders);

		$items = $this->itemRepository->getByIds($itemsIds);
		$users = $this->userRepository->getByIds($usersIds);

		$ordersCount = count($orders);
		for ($i = 0; $i < $ordersCount; $i++)
		{
			$orders[$i]->setItem($items[$i]);
			$orders[$i]->setUser($users[$i]);
		}

		return $orders;
	}

	public function getById(int $id): Order
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
		SELECT o.ID, o.NUMBER, o.USER_ID, o.ITEM_ID, o.STATUS_ID, o.PRICE, s.TITLE 
		FROM N_ONE_ORDERS o
		JOIN N_ONE_STATUSES s on s.ID = o.STATUS_ID
		WHERE o.ID = $id;
	");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		$order = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$order = new Order(
				$row['USER_ID'],
				$row['ITEM_ID'],
				$row['STATUS_ID'],
				$row['TITLE'],
				$row['PRICE'],
			);

			$order->setId($row['ID']);
			$order->setNumber($row['NUMBER']);
		}

		if ($order === null)
		{
			throw new RuntimeException("Item with id $id not found");
		}

		return $order;
	}

	public function add(Order|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$userId = $entity->getUserId();
		$itemId = $entity->getItemId();
		$statusId = $entity->getStatusId();
		$price = $entity->getPrice();
		$number = $entity->getNumber();
		$dateCreate = $entity->getDateCreate();

		$result = mysqli_query(
			$connection,
			"
		INSERT INTO N_ONE_ORDERS (USER_ID, ITEM_ID, STATUS_ID, PRICE, NUMBER, DATE_CREATE) 
		VALUES (
			$userId,
			$itemId,
			$statusId,
			$price,
		    '$number',
		    '$dateCreate'
		);"
		);

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}

	public function update(Order|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$orderId = $entity->getId();
		$userId = $entity->getUserId();
		$itemId = $entity->getItemId();
		$statusId = $entity->getStatusId();
		$price = $entity->getPrice();
		$number = $entity->getNumber();

		$result = mysqli_query(
			$connection,
			"
		UPDATE N_ONE_ORDERS 
		SET 
			USER_ID = $userId,
			ITEM_ID = $itemId,
			STATUS_ID = $statusId,
			PRICE = $price,
			NUMBER = '$number'
		where ID = $orderId;
		");


		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
}