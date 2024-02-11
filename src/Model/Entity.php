<?php

namespace N_ONE\App\Model;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

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
		$properties = $reflectionClass->getProperties(); // Get all properties
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

	/**
	 * @throws ReflectionException
	 */
	public static function createDummyObject(): object
	{
		$reflection = new ReflectionClass(static::class);
		$properties = $reflection->getProperties();

		$stubObject = $reflection->newInstanceWithoutConstructor();

		foreach ($properties as $property) {
			$property->setValue($stubObject, null);
		}

		return $stubObject;
	}
}