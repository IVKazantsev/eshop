<?php

namespace N_ONE\App\Model;

class Tag extends Entity
{
	public function __construct(
		protected ?int  $id,
		private ?string $title,
		private ?int    $parentId,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		return new static(
			$fields['id'], $fields['title'], $fields['parentId'],
		);
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