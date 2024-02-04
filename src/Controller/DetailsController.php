<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Controller\BaseController;
use N_ONE\App\Model\Item;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\App\Model\Repository;
use N_ONE\Core\TemplateEngine\TemplateEngine;

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

		return $this->renderLayout([
									   'content' => (new TemplateEngine(ROOT . '/src/View/'))->render(
										   'detailsPage', ['car' => $car]
									   ),
								   ]);
	}
}