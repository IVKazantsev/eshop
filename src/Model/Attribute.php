<?php

namespace N_ONE\App\Model;

class Attribute extends Entity
{
	public function __construct(
		protected int|null $id,
		private string     $title,
		private float|null   $value,
	)
	{}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function getValue(): ?float
	{
		return $this->value;
	}

	public function setValue(?int $value): void
	{
		$this->value = $value;
	}
}