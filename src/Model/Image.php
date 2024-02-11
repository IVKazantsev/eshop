<?php

namespace N_ONE\App\Model;

class Image extends Entity
{
	public function __construct(
		private int|null    $itemId,
		private string|null $path,
		private bool|null   $isMain,
		private int|null    $type,
		private int|null    $height,
		private int|null    $width,
	)
	{
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function setType(bool $type): void
	{
		$this->type = $type;
	}

	public function isMain(): bool
	{
		return $this->isMain;
	}

	public function setIsMain(bool $isMain): void
	{
		$this->isMain = $isMain;
	}

	public function getItemId(): int
	{
		return $this->itemId;
	}

	public function setItemId(int $itemId): void
	{
		$this->itemId = $itemId;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function setPath(string $path): void
	{
		$this->path = $path;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function setHeight(int $height): void
	{
		$this->height = $height;
	}

	public function getWidth(): int
	{
		return $this->width;
	}

	public function setWidth(int $width): void
	{
		$this->width = $width;
	}
}