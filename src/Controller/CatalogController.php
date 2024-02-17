<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Model\Service\PaginationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogController extends BaseController
{
	public function renderCatalog(?int $pageNumber, ?string $carsTag, ?string $SearchRequest, ?string $range): string
	{
		try
		{
			$filter = [
				'tag' => $carsTag,
				'title' => $SearchRequest,
				'pageNumber' => $pageNumber,
				'range' => $range,
			];

			$cars = $this->itemRepository->getList($filter);
			$previousPageUri = PaginationService::getPreviousPageUri($pageNumber);
			$nextPageUri = PaginationService::getNextPageUri(count($cars), $pageNumber);

			if (count($cars) === Configurator::option('NUM_OF_ITEMS_PER_PAGE') + 1)
			{
				array_pop($cars);
			}

			$content = TemplateEngine::render('pages/catalogPage', [
				'cars' => $cars,
				'previousPageUri' => $previousPageUri,
				'nextPageUri' => $nextPageUri,
			]);
		}

		catch (Exception)
		{
			$content = TemplateEngine::render('pages/errorPage', [
				'errorCode' => ':(',
				'errorMessage' => 'Товары не найдены',
			]);
		}

		return $this->renderPublicView($content, $SearchRequest);
	}
}