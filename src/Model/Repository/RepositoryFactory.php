<?php

namespace N_ONE\App\Model\Repository;

use InvalidArgumentException;
use N_ONE\App\Application;

class RepositoryFactory
{
	private array $repositoryMap;

	public function __construct()
	{
		$this->repositoryMap = [
			'items' => ItemRepository::class,
			'tags' => TagRepository::class,
			'orders' => OrderRepository::class,
			'users' => UserRepository::class,
			'attributes' => AttributeRepository::class,
		];
	}

	public function createRepository(string $className): Repository
	{
		if (!array_key_exists($className, $this->repositoryMap))
		{
			throw new InvalidArgumentException("There is no repository registered with class $className");
		}
		$repositoryClass = $this->repositoryMap[$className];

		return Application::getDI()->getComponent($this->getServiceName($repositoryClass));
	}

	public function getServiceName(string $className): string
	{
		$array = explode("\\", $className);

		return lcfirst(end($array));
	}
}