<?php

namespace N_ONE\App\Model;

class Order extends Entity
{
	public function __construct(
		protected int  $id,
		private int    $userId,
		private int    $itemId,
		private int    $statusId,
		private string $status,
		private int    $price,
		private ?User  $user = null,
		private ?Item  $item = null,
	){}

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

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function getItem(): Item
	{
		return $this->item;
	}

	public function setItem(Item $item): void
	{
		$this->item = $item;
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