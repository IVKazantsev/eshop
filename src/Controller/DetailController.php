<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class DetailController extends BaseController
{
	public function renderDetailPage(string $carId): string
	{
		try
		{
			$item = $this->itemRepository->getById($carId);
		}
		catch (DatabaseException)
		{
			$content = TemplateEngine::renderPublicError(':(', 'Something went wrong');
			return $this->renderPublicView($content);
		}

		if ($item === null)
		{
			http_response_code(404);
			$content = TemplateEngine::renderPublicError(404, "Page not found");
			return $this->renderPublicView($content);
		}

		$content = TemplateEngine::render('pages/detailPage', [
			'car' => $item,
		]);

		return $this->renderPublicView($content);
	}
}