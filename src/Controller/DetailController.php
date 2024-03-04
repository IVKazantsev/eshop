<?php

namespace N_ONE\App\Controller;

use mysqli_sql_exception;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class DetailController extends BaseController
{
	public function renderDetailPage(string $itemId): string
	{
		try
		{
			$item = $this->itemRepository->getById($itemId, true);
		}
		catch (DatabaseException $e)
		{
			Logger::error("Failed to fetch data from repository", $e->getFile(), $e->getLine());
			$content = TemplateEngine::renderPublicError(':(', 'Что-то пошло не так');

			return $this->renderPublicView($content);
		}
		catch (mysqli_sql_exception $e)
		{
			Logger::error("Failed to run query", $e->getFile(), $e->getLine());

			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		if ($item === null)
		{
			http_response_code(404);
			$content = TemplateEngine::renderPublicError(404, "Page not found");

			return $this->renderPublicView($content);
		}

		$content = TemplateEngine::render('pages/detailPage', [
			'item' => $item,
		]);

		return $this->renderPublicView($content);
	}
}