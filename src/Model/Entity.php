<?php

namespace N_ONE\App\Model;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionUnionType;

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
		$excludedFields = $this->getExcludedFields();

		return array_filter($result, static function($field) use ($excludedFields) {
			return !in_array($field, $excludedFields, true);
		});
	}

	/**
	 * @throws ReflectionException
	 */
	public static function createDummyObject(): object
	{
		$reflection = new ReflectionClass(static::class);
		$properties = $reflection->getProperties();

		$stubObject = $reflection->newInstanceWithoutConstructor();

		foreach ($properties as $property)
		{
			$property->setValue($stubObject, null);
		}

		return $stubObject;
	}

	/**
	 * @throws ReflectionException
	 */
	public function getPropertyType(string $propertyName): ?string
	{
		$reflectionClass = new ReflectionClass($this);
		$property = $reflectionClass->getProperty($propertyName);
		$type = $property->getType();

		if ($type instanceof ReflectionNamedType)
		{
			return $type->getName();
		}

		if ($type instanceof ReflectionUnionType)
		{
			// Обработка объединенных типов, например, "int|null"
			return implode('|', array_map(static function($type) {
				return $type->getName();
			}, $type->getTypes()));
		}

		return null; // Тип не определен или неизвестен
	}
}