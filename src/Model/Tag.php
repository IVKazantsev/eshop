<?php

namespace N_ONE\App\Model;

use ReflectionClass;
use ReflectionProperty;

class Tag extends Entity
{
	public function __construct(
		protected int|null  $id,
		private string|null $title
	)
	{
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