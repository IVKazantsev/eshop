<?php

namespace N_ONE\App\Model;

use RuntimeException;

class Item extends Entity
{
	/**
	 * @param Tag[] $tags
	 * @param Image[] $images
	 */
	public function __construct(
		protected int|null  $id,
		private string|null $title,
		private bool|null   $isActive,
		private int|null    $price,
		private string|null $description,
		private array|null  $tags,
		private array|null  $images,
		private int|null    $sortOrder = 0
	)
	{
	}

	public function getPreviewImage(): Image
	{
		foreach ($this->images as $image)
		{
			if ($image->getType() === 2 && $image->isMain())
			{
				return $image;
			}
		}
		throw new RuntimeException("Preview image for Item with id {$this->getId()} not found");
	}

	public function getFullSizeImages(): array
	{
		$images = [];
		foreach ($this->images as $image)
		{
			if ($image->getType() === 1)
			{
				$images[] = $image;
			}
		}
		if (empty($images))
		{
			throw new RuntimeException("FullSize image for Item with id {$this->getId()} not found");
		}

		return $images;
	}

	public function getExcludedFields(): array
	{
		return ['isActive', 'tags', 'images'];
	}

	public function getClassname()
	{
		$array = explode('\\', __CLASS__);

		return strtolower(end($array));
	}

	public function getField(string $fieldName)
	{
		return $this->$fieldName;
	}

	public function getImages(): array
	{
		return $this->images;
	}

	public function setImages(array $images): void
	{
		$this->images = $images;
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