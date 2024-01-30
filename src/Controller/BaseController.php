<?php

namespace N_ONE\src\Controller;
use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function render(string $templateName, array $params)
	{
		$template = __DIR__ . '/../View/' . $templateName;
		$templateEngine = new TemplateEngine(ROOT . '/src/View/');

		if (!file_exists($template))
		{
			http_response_code(404);
			return $templateEngine->render('errorPage');
		}

		return $templateEngine->render($templateName, $params);
	}
}