<?php

namespace N_ONE\App\Controller;

use Exception;

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
			echo $this->templateEngine->renderError(404, "Page not found");
			exit;
		}

		$catalog = $this->templateEngine->render('pages/catalogPage', [
			'cars' => $cars,
		]);

		return $this->templateEngine->render('layouts/publicLayout', [
			'content' => $catalog,
		]);
	}

}