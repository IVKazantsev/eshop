<?php

namespace N_ONE\src\Model;

class Item
{
	private string $title;
	private bool $isActive;
	private int $price;
	private string $description;
	private int $sortOrder;
	/**
	 * @var Tag[]
	 */
	private array $tags;

	/**
	 * @param Tag[] $tags
	 */
	public function __construct(
		string $title,
		bool   $isActive,
		int    $price,
		string $description,
		array  $tags,
		int    $sortOrder = 0
	)
	{
		$this->title = $title;
		$this->isActive = $isActive;
		$this->price = $price;
		$this->description = $description;
		$this->tags = $tags;
		$this->sortOrder = $sortOrder;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function isActive(): bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $isActive): void
	{
		$this->isActive = $isActive;
	}

	public function getPrice(): int
	{
		return $this->price;
	}

	public function setPrice(int $price): void
	{
		$this->price = $price;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	public function getSortOrder(): int
	{
		return $this->sortOrder;
	}

	public function setSortOrder(int $sortOrder): void
	{
		$this->sortOrder = $sortOrder;
	}

	public function getTags(): array
	{
		return $this->tags;
	}

	public function setTags(array $tags): void
	{
		$this->tags = $tags;
	}
}