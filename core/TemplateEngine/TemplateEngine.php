<?php

namespace N_ONE\Core\TemplateEngine;

use RuntimeException;

class TemplateEngine
{
	private string $templateDir;

	public function __construct(string $templateDir)
	{

		if (!is_dir($templateDir))
		{
			throw new RuntimeException('Invalid template dir');
		}

		$this->templateDir = $templateDir;
	}

	public function renderError(int $errorCode, string $errorMessage): string
	{
		$errorViewFile = 'errorPage';

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