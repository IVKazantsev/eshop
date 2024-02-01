<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function renderView(string $templateName, array $params): string
	{


		return (new TemplateEngine(__DIR__ . '../../../src/View/'))->render($templateName, $params);
	}
}