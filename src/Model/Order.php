<?php

namespace N_ONE\App\Model;

// use N_ONE\Core\Configurator\Configurator;

class Order extends Entity
{
	public function __construct(
		protected ?int           $id,
		private ?int             $userId,
		private ?int             $itemId,
		private ?int             $statusId,
		private ?string          $status,
		private ?int             $price,
		private readonly ?string $orderNumber,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		return new static(
			$fields['id'],
			$fields['userId'],
			$fields['itemId'],
			$fields['statusId'],
			$fields['status'],
			$fields['price'],
			$fields['orderNumber']
		);
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

	public function getNumber(): string
	{
		return $this->orderNumber;
	}
}