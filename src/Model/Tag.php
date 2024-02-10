<?php

namespace N_ONE\App\Model;

use ReflectionClass;
use ReflectionProperty;

class Tag extends Entity
{
	public function __construct(
		protected int|null $id,
		private string     $title
	)
	{
	}

	public function getInfoForTable(): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
		];
	}

	public function getExludedFields(): array
	{
		return [];
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