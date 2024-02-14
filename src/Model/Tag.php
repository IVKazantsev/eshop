<?php

namespace N_ONE\App\Model;

use ReflectionClass;
use ReflectionProperty;

class Tag extends Entity
{
	public function __construct(
		protected int|null $id,
		private string     $title,
		private int|null   $parentId,
		private int|null   $value
	)
	{}

	public function getValue(): ?int
	{
		return $this->value;
	}

	public function setValue(?int $value): void
	{
		$this->value = $value;
	}

	public function getParentId(): ?int
	{
		return $this->parentId;
	}

	public function setParentId(?int $parentId): void
	{
		$this->parentId = $parentId;
	}

	public function getClassname()
	{
		$array = explode('\\', __CLASS__);

		return strtolower(end($array));
	}

	public function getExcludedFields(): array
	{
		return [];
	}

	public function getField(string $fieldName)
	{
		return $this->$fieldName;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

}