<?php

namespace N_ONE\App\Controller;

use Exception;

class CatalogueController extends BaseController
{
	public function renderCatalogue(): string
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
		return $this->renderPublicView('pages/cataloguePage', [
			'cars' => $cars,
			'TE' => $this->templateEngine,
			]
		);
	}

}