<?php

namespace N_ONE\App\Controller;

use mysqli_sql_exception;
use N_ONE\App\Model\Service\PaginationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class CatalogController extends BaseController
{
	public function renderCatalog(?int $pageNumber, ?string $SearchRequest, ?array $tags, ?array $attributes): string
	{
		try
		{
			$filter = [
				'tags' => $tags,
				'title, description' => $SearchRequest,
				'pageNumber' => $pageNumber,
				'attributes' => $attributes,
				// 'sortOrder' => ['column' => 1, 'direction' => 'ASC']
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
			$sortAttributes = $this->attributeRepository->getList();
			$content = TemplateEngine::render('pages/catalogPage', [
				'items' => $items,
				'previousPageUri' => $previousPageUri,
				'nextPageUri' => $nextPageUri,
				'attributes' => $sortAttributes,
			]);

		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			$content = TemplateEngine::renderPublicError(':(', 'Что-то пошло не так');
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);

			return TemplateEngine::renderFinalError();
		}

		return $this->renderPublicView($content, $SearchRequest);
	}
}