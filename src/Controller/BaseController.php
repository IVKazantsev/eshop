<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	//Надо подумать над нэймингом, возникает путаница из-за двух методов render - Леша
	public function render(string $templateName, array $params)
	{

		return (new TemplateEngine(__DIR__ . '../../src/View/'))->render($templateName, $params);
	}
}