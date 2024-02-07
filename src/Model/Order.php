<?php

namespace N_ONE\App\Model;

use N_ONE\Core\Configurator\Configurator;

class Order extends Entity
{
	private string $number;
	private string $dateCreate;

	public function __construct(
		private int    $userId,
		private int    $itemId,
		private int    $statusId,
		private string $status,
		private int    $price,
	)
	{
	}

	public function generateNumber(int $time): void
	{
		$this->dateCreate = date('Y-m-d H:i:s', $time);

		$hashString = Configurator::option('ORDER_HASH_PREFIX') . $this->userId . $this->itemId . $this->dateCreate;
		$this->number = hash(Configurator::option('ORDER_HASH_ALGO'), $hashString);
	}

	public function getUserId(): int
	{
		return $this->userId;
	}

	public function setUserId(int $userId): void
	{
		$this->userId = $userId;
	}

	public function getItemId(): int
	{
		return $this->itemId;
	}

	public function setItemId(int $itemId): void
	{
		$this->itemId = $itemId;
	}

	public function getStatusId(): int
	{
		return $this->statusId;
	}

	public function setStatusId(int $statusId): void
	{
		$this->statusId = $statusId;
	}

	public function getStatus(): string
	{
		return $this->status;
	}

	public function setStatus(string $status): void
	{
		$this->status = $status;
	}

	public function getPrice(): int
	{
		return $this->price;
	}

	public function setPrice(int $price): void
	{
		$this->price = $price;
	}

	public function getNumber(): string
	{
		return $this->number;
	}

	public function setNumber(string $number): void
	{
		$this->number = $number;
	}

	public function getDateCreate(): string
	{
		return $this->dateCreate;
	}

	public function setDateCreate(string $dateCreate): void
	{
		$this->dateCreate = $dateCreate;
	}
}