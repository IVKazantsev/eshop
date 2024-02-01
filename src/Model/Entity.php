<?php

namespace N_ONE\App\Model;

abstract class Entity
{
	private int $id;
	abstract public function getId(): int;
	abstract public function setId(int $id): void;
}