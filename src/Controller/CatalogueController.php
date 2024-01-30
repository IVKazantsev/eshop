<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogueController extends BaseController
{

	public function action(string $message)
	{
		echo $message;
	}

	public function render(string $templateName, array $params)
	{


		return (new TemplateEngine(__DIR__ . '/../../src/View/'))->render($templateName, $params);
	}
}