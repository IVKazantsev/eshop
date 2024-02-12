<?php

namespace N_ONE\App\Controller;

use N_ONE\App\Model\Repository\ImageRepository;
use N_ONE\App\Model\Repository\ItemRepository;
use N_ONE\App\Model\Repository\OrderRepository;
use N_ONE\App\Model\Repository\RepositoryFactory;
use N_ONE\App\Model\Repository\TagRepository;
use N_ONE\App\Model\Repository\UserRepository;
use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function __construct(
		protected TagRepository     $tagRepository,
		protected ImageRepository   $imageRepository,
		protected ItemRepository    $itemRepository,
		protected UserRepository    $userRepository,
		protected OrderRepository   $orderRepository,
		protected RepositoryFactory $repositoryFactory
	)
	{
	}

	public function renderPublicView($content, string $currentSearchRequest = null): string
	{
		$tags = $this->tagRepository->getList();

		return TemplateEngine::render('layouts/publicLayout', [
			'tags' => $tags,
			'content' => $content,
			'currentSearchRequest' => $currentSearchRequest,
		]);
	}

	public function renderAdminView($content): string
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
		}
		$user = $this->userRepository->getById($_SESSION['user_id']);

		return TemplateEngine::render('layouts/adminLayout', [
			'user' => $user,
			'content' => $content,
		]);
	}

}