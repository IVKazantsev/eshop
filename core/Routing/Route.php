<?php

namespace N_ONE\Core\Routing;

class Route
{
	private array $variables = [];

	public function __construct(
		public string   $method,
		public string   $uri,
		public \Closure $action
	)
	{
	}

	public function getVariables(): array
	{
		return $this->variables;
	}
}