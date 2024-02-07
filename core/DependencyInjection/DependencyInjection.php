<?php

namespace N_ONE\Core\DependencyInjection;

use Exception;

class DependencyInjection
{
	private array $components = [];
	private string $configurationPath;

	public function __construct(string $configurationPath)
	{
		$this->configurationPath = $configurationPath;
		$this->configure();
	}

	private function configure(): void
	{

		if (!file_exists($this->configurationPath))
		{
			return;
		}

		$configuration = simplexml_load_file($this->configurationPath);

		foreach ($configuration as $service)
		{
			$arguments = [];
			$serviceName = (string)$service['name'];
			$className = (string)$service->class['name'];
			$isSingleton = (bool)$service->class['isSingleton'];

			foreach ($service->class as $class)
			{
				foreach ($class->arg as $arg)
				{
					$serviceArgument = (string)$arg['service'];
					if ($serviceArgument)
					{
						$arguments[] = [
							'service' => $serviceArgument,
						];
					}
				}
			}

			$this->components[$serviceName] = function () use ($className, $arguments, $isSingleton) {
				$loadedArguments = [];
				foreach ($arguments as $argument) {
					if ($argument['service']) {
						$loadedArguments[] = $this->getComponent($argument['service']);
					}
				}

				if ($isSingleton)
				{
					return $className::getInstance();
				}

				$reflection = new \ReflectionClass($className);
				return $reflection->newInstanceArgs($loadedArguments);
			};
		}
	}

	/**
	 * @throws Exception
	 */
	public function getComponent(string $serviceName)
	{
		if ($this->components[$serviceName])
		{
			return $this->components[$serviceName]();
		}
		else
		{
			throw new Exception('Service is not found');
		}

	}
}