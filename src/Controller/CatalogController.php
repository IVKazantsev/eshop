<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogController extends BaseController
{
	public function renderCatalog(string $carsTag = null, string $carsTitle= null): string
	{
		try
		{
			$filter = ['tag' => $carsTag, 'title' => $carsTitle];
			$cars = $this->itemRepository->getList($filter);
			$content = TemplateEngine::render('pages/catalogPage', [
				'cars' => $cars,
			]);
		}
		catch (Exception)
		{
			// http_response_code(404);
			// echo TemplateEngine::renderError(404, "Page not found");
			// exit;
			$content = TemplateEngine::render('pages/errorPage', [
				'errorCode' => ':(',
				'errorMessage' => 'Товары не найдены',
			]);
		}



		return $this->renderPublicView($content);
	}
}