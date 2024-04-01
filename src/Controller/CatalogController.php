<?php

namespace N_ONE\App\Controller;

use mysqli_sql_exception;
use N_ONE\App\Model\Service\PaginationService;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogController extends BaseController
{
	public function renderCatalog(?int    $pageNumber,
								  ?string $searchRequest,
								  ?array  $tags,
								  ?array  $attributes,
								  ?array  $sortOrder
	): string
	{
		try
		{
			$filter = [
				'tags' => $tags,
				'title, description' => ValidationService::validateFulltextField($searchRequest),
				'pageNumber' => $pageNumber,
				'attributes' => $attributes,
				'sortOrder' => $sortOrder,
			];

			$items = $this->itemRepository->getList($filter);
			$previousPageUri = PaginationService::getPreviousPageUri($pageNumber, $_SERVER['REQUEST_URI']);
			$nextPageUri = PaginationService::getNextPageUri(count($items), $pageNumber, $_SERVER['REQUEST_URI']);

			if (empty($items))
			{
				$content = TemplateEngine::renderPublicError(':(', 'Товары не найдены');

				return $this->renderPublicView($content, $searchRequest);
			}

			if (count($items) === Configurator::option('NUM_OF_ITEMS_PER_PAGE') + 1)
			{
				array_pop($items);
			}
			$sortAttributes = $this->attributeRepository->getList();
			$content = TemplateEngine::render('pages/catalogPage', [
				'items' => $items,
				'previousPageUri' => $previousPageUri,
				'nextPageUri' => $nextPageUri,
				'attributes' => $sortAttributes,
			]);
		}
		catch (DatabaseException $e)
		{
			Logger::error("Failed to fetch data from repository", $e->getFile(), $e->getLine());
			$content = TemplateEngine::renderPublicError(':(', 'Что-то пошло не так');
		}
		catch (mysqli_sql_exception $e)
		{
			Logger::error("Failed to run query", $e->getFile(), $e->getLine());
			return TemplateEngine::renderFinalError();
		}

		return $this->renderPublicView($content, $searchRequest);
	}
}