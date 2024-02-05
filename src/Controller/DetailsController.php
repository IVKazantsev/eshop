<?php

namespace N_ONE\App\Controller;

use Exception;

class DetailsController extends BaseController
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
		return $this->renderPublicView('pages/detailsPage', [
			'car' => $car,
			'TE' => $this->templateEngine,
			]
		);
	}

}