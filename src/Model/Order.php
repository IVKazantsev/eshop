<?php

namespace N_ONE\App\Model;

// use N_ONE\Core\Configurator\Configurator;

class Order extends Entity
{
	private string|null $dateCreate;

	public function __construct(
		protected int|null  $id,
		private int|null    $userId,
		private int|null    $itemId,
		private int|null    $statusId,
		private string|null $status,
		private int|null    $price,
	)
	{}

	public function getExcludedFields(): array
	{
		return ['dateCreate', 'statusId'];
	}

	public function generateNumber(int $time): void
	{
		$this->dateCreate = date('Y-m-d H:i:s', $time);

		// $hashString = Configurator::option('ORDER_HASH_PREFIX') . $this->userId . $this->itemId . $this->dateCreate;
		// $this->number = hash(Configurator::option('ORDER_HASH_ALGO'), $hashString);
	}

	public function getClassname(): string
	{
		$array = explode('\\', __CLASS__);

		return strtolower(end($array));
	}

	public function getField(string $fieldName)
	{
		return $this->$fieldName;
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

	public function getDateCreate(): string
	{
		return $this->dateCreate;
	}

	public function setDateCreate(string $dateCreate): void
	{
		$this->dateCreate = $dateCreate;
	}
}