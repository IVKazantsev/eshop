<?php

namespace N_ONE\App\Model;

use ReflectionClass;
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

	public function prepareEntityForTable(?array $excludeFields = []): array
	{
		// $reflector = new ReflectionClass($this);
		// $properties = $reflector->getProperties();

		$result = [];
		$excludeFields = array_flip($excludeFields);
		$item = array_diff_key(
			$this->getInfoForTable(),
			$excludeFields
		);
		foreach ($item as $fieldIndex => $value)
		{
			$result[$fieldIndex] = $value;
		}

		return $result;
	}

}
// public function getFieldNames(array $excludeFields = []): array
// {
// 	$serializedArray = (array)$this;
// 	$namespacePattern = '/^\\\\[a-zA-Z0-9_]+\\\\/'; // Pattern to match the namespace
//
// 	return array_map(function($key) use ($namespacePattern) {
// 		// Remove the namespace from the key
// 		return preg_replace($namespacePattern, '', $key);
// 	}, array_keys($serializedArray));
// }
//
// public function getFieldNamesForHeader(array $excludeFields = []): array
// {
// 	$reflector = new ReflectionClass($this);
// 	$properties = $reflector->getProperties(
// 	// ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE
// 	);
//
// 	// Get the names of the properties and exclude the specified ones
// 	return array_diff(
// 		array_map(function(ReflectionProperty $property) {
// 			return $property->getName();
// 		}, $properties),
// 		$excludeFields
// 	);
// }