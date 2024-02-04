<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function renderView(string $templateName, array $params): string
	{
		$TE = (new TemplateEngine(ROOT . '/src/View/'));

		return $TE->render($templateName, $params);
	}

	public function renderLayout(array $params): string
	{
		$TE = (new TemplateEngine(ROOT . '/src/View/'));

		return $TE->render('layout', $params);
	}
}