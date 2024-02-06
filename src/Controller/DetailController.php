<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class DetailController extends BaseController
{
	public function renderDetailPage(string $carId): string
	{
		try
		{
			$car = $this->itemRepository->getById($carId);
		}
		catch (Exception)
		{
			http_response_code(404);
			echo TemplateEngine::renderError(404, "Page not found");
			exit;
		}

		$detailPage = TemplateEngine::render('pages/detailPage', [
			'car' => $car,
		]);

		return $this->renderPublicView($detailPage);
	}
}