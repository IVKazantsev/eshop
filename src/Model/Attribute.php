<?php

namespace N_ONE\App\Model;

class Attribute extends Entity
{
	public function __construct(
		protected ?int  $id,
		private ?string $title,
		private ?float  $value,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		$new = new static(
			$fields['id'], $fields['title'], $fields['value'],
		);

		return $new;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getExcludedFields(): array
	{
		return ['value'];
	}

	public function getClassname(): string
	{
		$array = explode('\\', __CLASS__);

		return strtolower(end($array));
	}

	public function getField(string $fieldName)
	{
		return $this->$fieldName;
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