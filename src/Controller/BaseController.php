<?php

namespace N_ONE\App\Controller;

use mysqli_sql_exception;
use N_ONE\App\Model\Repository\AttributeRepository;
use N_ONE\App\Model\Repository\ImageRepository;
use N_ONE\App\Model\Repository\ItemRepository;
use N_ONE\App\Model\Repository\OrderRepository;
use N_ONE\App\Model\Repository\RepositoryFactory;
use N_ONE\App\Model\Repository\TagRepository;
use N_ONE\App\Model\Repository\UserRepository;
use N_ONE\App\Model\Service\TagService;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
	public function __construct(

		protected TagRepository     $tagRepository,
		protected ImageRepository   $imageRepository,
		protected ItemRepository    $itemRepository,
		protected UserRepository    $userRepository,
		protected OrderRepository   $orderRepository,
		protected AttributeRepository $attributeRepository,
		protected RepositoryFactory $repositoryFactory
	)
	{}

	public function renderPublicView($content, string $currentSearchRequest = null): string
	{
		try
		{
			$tags = TagService::reformatTags($this->tagRepository->getAll());
			$attributes = $this->attributeRepository->getList();
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			return TemplateEngine::renderPublicError(';(', "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return TemplateEngine::render('layouts/publicLayout', [
			'tags' => $tags,
			'attributes' =>$attributes,
			'content' => $content,
			'currentSearchRequest' => $currentSearchRequest,
		]);
	}

	public function renderAdminView($content): string
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}

		try
		{
			$user = $this->userRepository->getById($_SESSION['user_id']);
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			return TemplateEngine::renderAdminError(';(', "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return TemplateEngine::render('layouts/adminLayout', [
			'user' => $user,
			'content' => $content,
		]);
	}

}