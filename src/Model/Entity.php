<?php

namespace N_ONE\App\Model;

abstract class Entity
{
	protected ?int $id = null;
	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}
}