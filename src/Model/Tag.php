<?php

namespace N_ONE\App\Model;

class Tag
{
	private string $title;

	public function __construct(string $title)
	{
		$this->title = $title;
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