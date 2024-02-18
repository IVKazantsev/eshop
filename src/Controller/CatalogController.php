<?php

namespace N_ONE\App\Controller;

use N_ONE\App\Model\Service\PaginationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
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

			$items = $this->itemRepository->getList($filter);
			$previousPageUri = PaginationService::getPreviousPageUri($pageNumber);
			$nextPageUri = PaginationService::getNextPageUri(count($items), $pageNumber);

			if (empty($items))
			{
				$content = TemplateEngine::renderPublicError(':(', 'Товары не найдены');

				return $this->renderPublicView($content, $SearchRequest);
			}

			if (count($items) === Configurator::option('NUM_OF_ITEMS_PER_PAGE') + 1)
			{
				array_pop($items);
			}
			$content = TemplateEngine::render('pages/catalogPage', [
				'cars' => $items,
				'previousPageUri' => $previousPageUri,
				'nextPageUri' => $nextPageUri,
			]);

		}
		catch (DatabaseException)
		{
			$content = TemplateEngine::renderPublicError(':(', 'Something went wrong');
		}

		return $this->renderPublicView($content, $SearchRequest);
	}
}