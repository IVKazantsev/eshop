<?php

namespace N_ONE\App\Model;

class Order extends Entity
{
	public function __construct(
		private int    $id,
		private User   $user,
		private Item   $item,
		private int    $statusId,
		private string $status,
		private int    $price
	){}

	public function getStatusId(): int
	{
		return $this->statusId;
	}

	public function setStatusId(int $statusId): void
	{
		$this->statusId = $statusId;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
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