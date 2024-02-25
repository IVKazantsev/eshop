<?php

namespace N_ONE\App\Model;

class Tag extends Entity
{
	public function __construct(
		protected int|null  $id,
		private string|null $title,
		private int|null    $parentId,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		$new = new static(
			$fields['id'], $fields['title'], $fields['parentId'],
		);

		return $new;
	}

	public function getParentId(): ?int
	{
		return $this->parentId;
	}

	public function setParentId(?int $parentId): void
	{
		$this->parentId = $parentId;
	}

	public function getClassname(): string
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