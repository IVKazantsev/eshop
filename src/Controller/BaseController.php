<?php

namespace N_ONE\App\Controller;

use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\TemplateEngine\TemplateEngine;
use N_ONE\App\Model\Repository;

abstract class BaseController
{
	protected Repository\TagRepository $tagRepository;
	protected Repository\ImageRepository $imageRepository;
	protected Repository\ItemRepository $itemRepository;

	public function __construct()
	{
		$dbConnection = DbConnector::getInstance();
		$this->tagRepository = new Repository\TagRepository($dbConnection);
		$this->imageRepository = new Repository\ImageRepository($dbConnection);
		$this->itemRepository = new Repository\ItemRepository(
			$dbConnection, $this->tagRepository, $this->imageRepository
		);
	}

	public function renderPublicView($content): string
	{
		$tags = $this->tagRepository->getList();

		return TemplateEngine::render('layouts/publicLayout', [
			'tags' => $tags,
			'content' => $content,
		]);
	}
}