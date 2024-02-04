<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function renderView(string $templateName, array $params): string
	{


		return (new TemplateEngine(Configurator::option("VIEWS_PATH")))->render($templateName, $params);
	}
}