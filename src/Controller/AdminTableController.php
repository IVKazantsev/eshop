<?php

namespace N_ONE\App\Controller;

use N_ONE\App\Controller\BaseController;
use N_ONE\App\Model\Service\PaginationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class AdminTableController extends BaseController
{
	public function renderEntityPage(string $entityToDisplay, ?int $pageNumber, int $isActive): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToDisplay);

			$filter = [
				'pageNumber' => $pageNumber,
				'isActive' => $isActive,
			];

			$entities = $repository->getList($filter);
			$previousPageUri = PaginationService::getPreviousPageUri($pageNumber, $_SERVER['REQUEST_URI']);
			$nextPageUri = PaginationService::getNextPageUri(count($entities), $pageNumber, $_SERVER['REQUEST_URI']);

			if (empty($entities))
			{
				$className = 'N_ONE\App\Model\\' . ucfirst(
						substr_replace($entityToDisplay, '', -1)
					);
				$entities['dummy'] = ($className)::createDummyObject();

				return $this->renderAdminView(TemplateEngine::render('pages/adminEntitiesPage', [
					'entities' => $entities,
					'isActive' => $isActive,
				]));
			}

			if (count($entities) === Configurator::option('NUM_OF_ITEMS_PER_PAGE') + 1)
			{
				array_pop($entities);
			}

			$content = TemplateEngine::render('pages/adminEntitiesPage', [
				'entities' => $entities,
				'previousPageUri' => $previousPageUri,
				'nextPageUri' => $nextPageUri,
				'isActive' => $isActive,
			]);

		}
		catch (InvalidArgumentException)
		{
			// Не получилось создать репозиторий. Логирование не нужно
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);

			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderAdminView($content);
	}
}