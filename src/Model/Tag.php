<?php

namespace N_ONE\App\Model;

class Tag extends Entity
{
	public function __construct(
		private string $title
	){}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}
}