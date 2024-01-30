<?php

namespace N_ONE\src\Model;

class Order
{
	private User $user;
	private Item $item;
	private string $status;
	private int $price;

	public function __construct(User $user, Item $item, string $status, int $price)
	{
		$this->user = $user;
		$this->item = $item;
		$this->status = $status;
		$this->price = $price;
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