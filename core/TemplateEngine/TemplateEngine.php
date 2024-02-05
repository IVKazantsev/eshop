<?php

namespace N_ONE\Core\TemplateEngine;

use N_ONE\Core\Configurator\Configurator;
use RuntimeException;

class TemplateEngine
{
	static private ?TemplateEngine $instance = null;
	private string $templateDir;

	private function __construct()
	{
		$templateDir = Configurator::option("VIEWS_PATH");
		if (!is_dir($templateDir))
		{
			throw new RuntimeException('Invalid template dir');
		}

		$this->templateDir = $templateDir;
	}

	private function __clone()
	{
	}

	public static function getInstance(): TemplateEngine
	{
		if (static::$instance)
		{
			return static::$instance;
		}

		return static::$instance = new self();
	}

	public function renderError(int $errorCode, string $errorMessage): string
	{
		$errorViewFile = 'pages/errorPage';

		$variables = [
			'errorCode' => $errorCode,
			'errorMessage' => $errorMessage,
		];

		return $this->render($errorViewFile, $variables);
	}

	public function render(string $file, array $variables = []): string
	{
		if (!preg_match('/^[0-9A-Za-z\/_-]+$/', $file))
		{
			throw new RuntimeException('Invalid template path');
		}

		$absolutePath = $this->templateDir . $file . ".php";
		if (!file_exists($absolutePath))
		{
			exit(404);
		}

		extract($variables);

		ob_start();

		require $absolutePath;

		return ob_get_clean();
	}
}