<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogController extends BaseController
{
	public function renderCatalog(): string
	{
		try
		{
			$cars = $this->itemRepository->getList();
		}
		catch (Exception)
		{
			http_response_code(404);
			echo TemplateEngine::renderError(404, "Page not found");
			exit;
		}

		$catalog = TemplateEngine::render('pages/catalogPage', [
			'cars' => $cars,
		]);

		return $this->renderPublicView($catalog);
	}
}