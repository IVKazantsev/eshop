<?php

namespace N_ONE\App\Controller;

use Exception;

class DetailController extends BaseController
{
	public function renderDetails(string $carId): string
	{
		try
		{
			$car = $this->itemRepository->getById($carId);
		}
		catch (Exception)
		{
			http_response_code(404);
			echo $this->templateEngine->renderError(404, "Page not found");
			exit;
		}

		$detailPage = $this->templateEngine->render('pages/detailPage', [
			'car' => $car,
		]);

		return $this->renderPublicView($detailPage);
	}
}