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

	public function render(string $file, array $variables = [], $isEcho = true): string
	{
		if (!preg_match('/^[0-9A-Za-z\/_-]+$/', $file))
		{
			throw new RuntimeException('Invalid template path');
		}

		$absolutePath = $this->templateDir . $file . ".php";

		if (!file_exists($absolutePath))
		{
			throw new RuntimeException('Template not found');
		}

		extract($variables);

		ob_start();

		require $absolutePath;

		$content = ob_get_clean();
		if ($isEcho)
		{
			echo $content;
		}

		return $content;
	}
}