<?php

namespace N_ONE\src\Controller;
use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function render(string $templateName, array $params)
	{
		return (new TemplateEngine(__DIR__ . '../../src/View/'))->render($templateName, $params);
	}
}