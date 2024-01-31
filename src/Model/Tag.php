<?php

namespace N_ONE\App\Model;

class Tag
{
	public function __construct(
		private int    $id,
		private string $title
	)
	{
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
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