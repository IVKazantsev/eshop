<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\TemplateEngine\TemplateEngine;
use N_ONE\App\Model\Repository;

abstract class BaseController
{
	protected TemplateEngine $templateEngine;
	protected Repository\TagRepository $tagRepository;
	protected Repository\ImageRepository $imageRepository;
	protected Repository\ItemRepository $itemRepository;

	public function __construct()
	{
		$dbConnection = DbConnector::getInstance();
		$this->templateEngine = new TemplateEngine(ROOT . '/src/View/');
		$this->tagRepository = new Repository\TagRepository($dbConnection);
		$this->imageRepository = new Repository\ImageRepository($dbConnection);
		$this->itemRepository = new Repository\ItemRepository(
			$dbConnection,
			$this->tagRepository,
			$this->imageRepository
		);
	}

	public function renderView(string $templateName, array $params): string
	{
		$TE = (new TemplateEngine(ROOT . '/src/View/'));

		return $TE->render($templateName, $params);
	}

	public function renderLayout(array $params): string
	{
		$TE = (new TemplateEngine(ROOT . '/src/View/'));

		return $TE->render('layout', $params);
	}
}