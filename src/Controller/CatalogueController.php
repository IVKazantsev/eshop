<?php

namespace N_ONE\App\Controller;

use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\App\Model\Repository;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogueController extends BaseController
{

	public function action(string $message): void
	{
		echo $message;
	}

	public function renderCatalogue(): string
	{

		$cars = [];
		foreach ($this->itemRepository->getList() as $item)
		{
			$cars[] = $item;
		}

		return $this->renderLayout([
									   'content' => $this->templateEngine->render(
										   'cataloguePage', ['cars' => $cars]
									   ),
								   ]);
	}

}