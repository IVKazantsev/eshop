<?php

namespace N_ONE\App\Model;

// use N_ONE\Core\Configurator\Configurator;

class Order extends Entity
{
	public function __construct(
		protected int|null  $id,
		private int|null    $userId,
		private int|null    $itemId,
		private int|null    $statusId,
		private string|null $status,
		private int|null    $price,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		$new = new static(
			$fields['id'],
			$fields['userId'],
			$fields['itemId'],
			$fields['statusId'],
			$fields['status'],
			$fields['price'],
		);

		return $new;
	}

	public function getExcludedFields(): array
	{
		return ['statusId'];
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
}