<?php

namespace N_ONE\App\Model;

use ReflectionClass;

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

	public function getFieldNames(bool $getAll = false): array
	{
		$reflectionClass = new ReflectionClass($this);
		$properties = $reflectionClass->getProperties();
		$result = [];
		foreach ($properties as $property)
		{
			$result[] = $property->getName();
		}
		if ($getAll)
		{
			return $result;
		}

		return array_flip(array_diff_key(array_flip($result), array_flip($this->getExcludedFields())));
	}
}