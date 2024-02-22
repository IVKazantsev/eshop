<?php

namespace N_ONE\App\Controller;

use Exception;
use InvalidArgumentException;
use mysqli_sql_exception;
use N_ONE\App\Model\Attribute;
use N_ONE\App\Model\Image;
use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\ImageService;
use N_ONE\App\Model\Service\PaginationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Exceptions\LoginException;
use N_ONE\Core\Exceptions\ValidateException;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\User;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;
use N_ONE\App\Model\Service\ValidationService;

class AdminController extends BaseController
{
	public static function displayLoginError(): void
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
		if (isset($_SESSION['login_error']))
		{
			echo '<div class="error-message">' . $_SESSION['login_error'] . '</div>';
			unset($_SESSION['login_error']);
		}
	}

	public function login(string $email, ?string $password): void
	{
		$trimmedEmail = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
		$trimmedPassword = trim($password);
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}

		try
		{
			if (empty($trimmedEmail) || empty($trimmedPassword))
			{
				$_SESSION['login_error'] = 'Please enter both email and password.';
				throw new LoginException();
			}

			$user = $this->userRepository->getByEmail($trimmedEmail);
			if (!$user)
			{
				throw new LoginException();
			}

			if ($user->getRoleId() !== 1)
			{
				$_SESSION['login_error'] = 'Insufficient rights';
				throw new LoginException();
			}

			if ($trimmedPassword !== $user->getPass())
			{
				$_SESSION['login_error'] = 'Incorrect email or password. Please try again.';
				throw new LoginException();
			}
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			echo TemplateEngine::renderPublicError(';(', "Что-то пошло не так");
			exit();
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			echo TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
			exit();
		}
		catch (LoginException)
		{
			if (!$_SESSION['login_error'])
			{
				$_SESSION['login_error'] = 'Incorrect email or password. Please try again.';
			}
			Router::redirect('/login');
			exit(401);
		}
		ob_start();
		$_SESSION['user_id'] = $user->getId();
		Router::redirect('/admin');
		ob_end_flush();
		exit();
	}

	public function renderEditPage(string $entityToEdit, string $entityId): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToEdit);
			$entity = $repository->getById($entityId);
			if ($entity === null)
			{
				$content = TemplateEngine::renderAdminError(':(', 'Данный товар не найден');

				return $this->renderAdminView($content);
			}
			switch (get_class($entity))
			{
				case Item::class:
				{
					$parentTags = $this->tagRepository->getParentTags();
					$attributes = $this->attributeRepository->getList();
					$itemAttributes = $entity->getAttributes();
					$itemTags = [];
					$childrenTags = [];
					$specificFields = [
						'isActive' => TemplateEngine::render('components/editIsActive', [
							'entity' => $entity,
						]),
						'description' => TemplateEngine::render('components/editItemDescription', [
							'item' => $entity,
						]),
					];
					foreach ($parentTags as $parentTag)
					{
						$childrenTags[(string)($parentTag->getTitle())] = $this->tagRepository->getByParentId(
							$parentTag->getId()
						);

					}
					foreach ($entity->getTags() as $tag)
					{
						$itemTags[$tag->getParentId()] = $tag->getId();

					}
					$tagsSection = TemplateEngine::render('components/editPageTagsSection', [
						'childrenTags' => $childrenTags,
						'itemTags' => $itemTags,
					]);
					$attributesSection = TemplateEngine::render('components/editPageAttributesSection', [
						'attributes' => $attributes,
						'itemAttributes' => $itemAttributes,
					]);

					$images = $this->imageRepository->getList([$entityId]);
					$addImagesSection = TemplateEngine::render('components/addImagesSection', [
						'itemId' => $entity,
					]);
					$deleteImagesSection = TemplateEngine::render('components/deleteImagesSection', [
						'images' => $images[$entityId] ?? [],
					]);

					$additionalSections = [
						$tagsSection,
						$attributesSection,
						$addImagesSection,
						$deleteImagesSection,
					];

					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
						'specificFields' => $specificFields,
						'additionalSections' => $additionalSections,
					]);
					break;
				}
				case Tag::class:
				{
					$parentTags = $repository->getAllParentTags();
					$specificFields = [
						'parentId' => TemplateEngine::render('components/editTagParentId', [
							'tag' => $entity,
							'parentTags' => $parentTags,
						]),
					];
					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
						'specificFields' => $specificFields,
					]);
					break;

				}
				case Attribute::class:
				case User::class:
				{
					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
					]);
					break;

				}
				case Order::class:
				{
					$statuses = $this->orderRepository->getStatuses();
					$specificFields = [
						'status' => TemplateEngine::render('components/editOrderStatusField', ['statuses' => $statuses]
						),
						'statusId' => TemplateEngine::render('components/editOrderStatusIdField', ['order' => $entity]),
					];
					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
						'specificFields' => $specificFields,
					]);
					break;

				}
				default:
				{
					$content = TemplateEngine::render('pages/adminEditPage');
					break;

				}
			}
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
		catch (InvalidArgumentException)
		{
			Logger::error("Failed to create repository", __METHOD__);
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
		}

		return $this->renderAdminView($content);
	}

	public function renderLoginPage(string $view, array $params): string
	{
		static::displayLoginError();

		return TemplateEngine::render("pages/$view", $params);
	}

	public function renderDashboard(): string
	{
		if (!$this->checkIfLoggedIn())
		{
			Router::redirect('/login');
		}
		try
		{
			$content = TemplateEngine::render('pages/adminDashboard');
		}
		catch (Exception)
		{
			$content = TemplateEngine::renderAdminError(':(', 'Товары не найдены');
		}

		return $this->renderAdminView($content);
	}

	public function checkIfLoggedIn(): bool
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
		if (!isset($_SESSION['user_id']))
		{
			return false;
		}

		return true;
	}

	public function renderEntityPage(string $entityToDisplay, ?int $pageNumber): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToDisplay);

			$filter = [
				'pageNumber' => $pageNumber,
			];

			$entities = $repository->getList($filter);
			$previousPageUri = PaginationService::getPreviousPageUri($pageNumber);
			$nextPageUri = PaginationService::getNextPageUri(count($entities), $pageNumber);

			if (empty($entities))
			{
				$content = TemplateEngine::renderAdminError(':(', 'Сущности не найдены');

				return $this->renderAdminView($content);
			}

			if ($entityToDisplay === 'items' && count($entities) === Configurator::option('NUM_OF_ITEMS_PER_PAGE') + 1)
			{
				array_pop($entities);
			}

			$content = TemplateEngine::render('pages/adminEntitiesPage', [
				'entities' => $entities,
				'previousPageUri' => $previousPageUri,
				'nextPageUri' => $nextPageUri,
			]);

		}
		catch (InvalidArgumentException)
		{
			Logger::error("Failed to create repository", __METHOD__);
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

	/**
	 * @throws ValidateException
	 * @throws DatabaseException
	 */
	public function updateEntity(string $entityType, string $entityId): string
	{
		$fields = $_POST;

		// foreach ($fields as $field)
		// {
		// 	$fields[$field] = ValidationService::validateEntryField($field);
		// }
		$className = 'N_ONE\App\Model\\' . ucfirst(
				substr_replace($entityType, '', -1)
			); //Костыль на приведение названия типа сущности из URL к названию класса
		if ($entityType === 'tags')
		{
			foreach ($fields as $field => $value)
			{
				if ($field === 'parentId' && $value === '')
				{
					$fields[$field] = null;
					continue;
				}
				$fields[$field] = ValidationService::validateEntryField($value);
			}
		}
		if ($entityType === 'attributes')
		{
			$fields['value'] = null;
			// foreach ($fields as $field => $value)
			// {
			// $fields[$field] = ValidationService::validateEntryField($value);
			// }
		}
		if ($entityType === 'items')
		{
			// $tags = [];
			if (array_key_exists('imageIds', $fields))
			{
				$this->deleteImages($fields['imageIds']);
			}
			if ($_FILES['image']['size'][0] !== 0)
			{
				$this->addBaseImages($_FILES, $entityId);
			}

			foreach ($fields as $field => $value)
			{
				if (
					($field === 'isActive' || $field === 'sortOrder')
					&& $value === '0'
				) //РАЗРЕШЕНИЕ НА ИСПОЛЬЗОВАНИЕ FALSY ДЛЯ УКАЗАННЫХ ПОЛЕЙ
				{
					continue;
				}
			}

			$fields['images'] = [];
			if (!isset($fields["tags"]))
			{
				array_splice($fields, array_search("attributes", array_keys($fields)), 0, ["tags" => []]);
			}
			$fields["attributes"] = array_filter($fields["attributes"], function($value) {return is_numeric($value);});
		}
		// foreach ($fields as $field => $value)
		// {
		// 	if (!trim($value))
		// 	{
		// 		return TemplateEngine::renderAdminError(404, "Missing required field: {$field}");
		// 	}
		// 	$fields[$field] = trim($value);
		// }

		try
		{
			$repository = $this->repositoryFactory->createRepository($entityType);
			$entity = new $className($entityId, ...array_values($fields));
			$repository->update($entity);
		}
		catch (ValidateException $e)
		{
			return TemplateEngine::renderAdminError(400, $e->getMessage());
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			return TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderSuccessEditPage();
	}

	public function renderSuccessEditPage(): string
	{
		if ($_SERVER['REQUEST_URI'] !== "/admin/edit/success")
		{
			Router::redirect("/admin/edit/success");
		}

		$successEditPage = TemplateEngine::render(
			'pages/successEditPage'
		);

		return $this->renderAdminView($successEditPage);
	}

	public function renderSuccessAddPage(): string
	{
		if ($_SERVER['REQUEST_URI'] !== "/admin/add/success")
		{
			Router::redirect("/admin/add/success");
		}

		$successAddPage = TemplateEngine::render(
			'pages/successAddPage'
		);

		return $this->renderAdminView($successAddPage);
	}

	public function logout(): void
	{
		session_start();
		$_SESSION = [];
		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}
		session_destroy();
		Router::redirect('/login');
	}

	public function renderConfirmDeletePage(string $entities, string $entityId): string
	{
		$entity = substr($entities, 0, -1);
		$confirmDeletePage = TemplateEngine::render('pages/confirmDeletePage', [
			'entity' => $entity,
			'entityId' => $entityId,
		]);

		return $this->renderAdminView($confirmDeletePage);
	}

	public function processDeletion(string $entities, string $entityId): string
	{
		if (!$entityId)
		{
			return TemplateEngine::renderAdminError(404, "Страница не найдена");
		}
		try
		{
			$repository = $this->repositoryFactory->createRepository($entities);
			$repository->delete($entities, $entityId);
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			return TemplateEngine::renderAdminError(":(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}
		catch (InvalidArgumentException)
		{
			Logger::error("Failed to create repository", __METHOD__);
			return TemplateEngine::renderAdminError(":(", "Что-то пошло не так");

		}

		return $this->renderSuccessDeletePage();
	}

	public function renderSuccessDeletePage(): string
	{
		if ($_SERVER['REQUEST_URI'] !== "/admin/delete/success")
		{
			Router::redirect("/admin/delete/success");
		}

		$successDeletePage = TemplateEngine::render(
			'pages/successDeletePage'
		);

		return $this->renderAdminView($successDeletePage);
	}

	/**
	 * @throws DatabaseException
	 */
	public function deleteImages(array $imagesIds): bool
	{
		$imagesIds = array_map('intval', $imagesIds);
		$images = $this->imageRepository->getList($imagesIds, true);
		$path = ROOT . '/public' . Configurator::option('IMAGES_PATH');

		$this->imageRepository->permanentDeleteByIds($imagesIds);

		foreach ($imagesIds as $id)
		{
			unlink($path . $images[$id][0]->getPath());
		}

		return true;
	}

	/**
	 * @throws ValidateException
	 * @throws DatabaseException
	 */
	public function addBaseImages($files, $itemId): bool
	{
		$fileCount = count($files['image']['name']);

		for ($i = 0; $i < $fileCount; $i++)
		{
			ValidationService::validateImage($files, $i);

			$targetDir = ROOT
				. '/public'
				. Configurator::option('IMAGES_PATH')
				. "$itemId/"; // директория для сохранения загруженных файлов
			$targetFile = $targetDir . basename($files["image"]["name"][$i]);
			$file_extension = pathinfo($files['image']['name'][$i], PATHINFO_EXTENSION);

			ImageService::createDirIfNotExist($targetDir);

			$fullSizeImageId = $this->imageRepository->add(
				new Image(null, $itemId, false, 1, 1200, 900, $file_extension)
			);
			$previewImageId = $this->imageRepository->add(
				new Image(null, $itemId, false, 2, 640, 480, $file_extension)
			);

			$finalFullSizePath = $targetDir . $fullSizeImageId . "_1200_900_fullsize_base" . ".$file_extension";
			$finalPreviewPath = $targetDir . $previewImageId . '_640_480_preview_base' . ".$file_extension";
			// Попытка загрузки файла на сервер
			if (move_uploaded_file($files["image"]["tmp_name"][$i], $targetFile))
			{
				ImageService::resizeImage($targetFile, $finalFullSizePath, 1200, 900);
				ImageService::resizeImage($targetFile, $finalPreviewPath, 640, 480);
				unlink($targetFile);
			}
			else
			{
				return false;
			}
		}

		return true;
	}

	public function renderAddPage(string $entityToAdd): string
	{
		$repository = $this->repositoryFactory->createRepository($entityToAdd . 's');
		$className = 'N_ONE\App\Model\\' . ucfirst(
				$entityToAdd
			);//Костыль на приведение названия типа сущности из URL к названию класса

		$entity = ($className)::createDummyObject();
		try
		{
			switch (get_class($entity))
			{
				case Item::class:
				{
					$parentTags = $this->tagRepository->getParentTags();
					$attributes = $this->attributeRepository->getList();

					$itemTags = [];
					$childrenTags = [];
					$specificFields = [
						'isActive' => TemplateEngine::render('components/editIsActive', [
							'entity' => $entity,
						]),
						'description' => TemplateEngine::render('components/editItemDescription', [
							'item' => $entity,
						]),
					];
					foreach ($parentTags as $parentTag)
					{
						$childrenTags[(string)($parentTag->getTitle())] = $this->tagRepository->getByParentId(
							$parentTag->getId()
						);

				}
				$tagsSection = TemplateEngine::render('components/editPageTagsSection', [
					'childrenTags' => $childrenTags,
					'itemTags' => $itemTags,
				]);
				$attributesSection = TemplateEngine::render('components/editPageAttributesSection', [
					'attributes' => $attributes,
				]);


				$deleteImagesSection = TemplateEngine::render(
					'components/deleteImagesSection',
					[]
				);

				$additionalSections = [
					$tagsSection,
					$attributesSection,
					$deleteImagesSection,
				];

					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
						'specificFields' => $specificFields,
						'additionalSections' => $additionalSections,
					]);
					break;
				}
				case Tag::class:
				{
					$parentTags = $repository->getAllParentTags();
					$specificFields = [
						'parentId' => TemplateEngine::render('components/editTagParentId', [
							'tag' => $entity,
							'parentTags' => $parentTags,
						]),
					];
					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
						'specificFields' => $specificFields,
					]);
					break;

				}
				case Attribute::class:
				case User::class:
				{
					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
					]);
					break;

				}
				case Order::class:
				{
					$statuses = $this->orderRepository->getStatuses();
					$specificFields = [
						'status' => TemplateEngine::render('components/editOrderStatusField', ['statuses' => $statuses]
						),
						'statusId' => TemplateEngine::render('components/editOrderStatusIdField', ['order' => $entity]),
					];
					$content = TemplateEngine::render('pages/adminEditPage', [
						'entity' => $entity,
						'specificFields' => $specificFields,
					]);
					break;

				}
				default:
				{
					$content = TemplateEngine::render('pages/adminEditPage');
					break;

				}
			}
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			return TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderAdminView($content);
	}

	public function addEntity(string $entityToAdd): string
	{

		$fields = $_POST;
		// foreach ($fields as $field)
		// {
		// 	$fields[$field] = ValidationService::validateEntryField($field);
		// }
		$className = 'N_ONE\App\Model\\' . ucfirst(
				$entityToAdd
			); //Костыль на приведение названия типа сущности из URL к названию класса

		try
		{
			if ($entityToAdd === 'tag')
			{
				foreach ($fields as $field => $value)
				{
					$fields[$field] = ValidationService::validateEntryField($value);
				}
				if (!array_key_exists('parentId', $fields))
				{
					echo 'true';
					$fields['parentId'] = null;
				}
			}
		}
		catch (ValidateException $e)
		{
			return TemplateEngine::renderAdminError(400, $e->getMessage());
		}
		if ($entityToAdd === 'attribute')
		{
			$fields['value'] = null;
			// foreach ($fields as $field => $value)
			// {
			// $fields[$field] = ValidationService::validateEntryField($value);
			// }
		}
		if ($entityToAdd === 'item')
		{
			foreach ($fields as $field => $value)
			{
				if (
					($field === 'isActive' || $field === 'sortOrder')
					&& $value === '0'
				) //РАЗРЕШЕНИЕ НА ИСПОЛЬЗОВАНИЕ FALSY ДЛЯ УКАЗАННЫХ ПОЛЕЙ
				{
					continue;
				}
			}

			$fields['images'] = [];

			if (!isset($fields["tags"]))
			{
				array_splice($fields, array_search("attributes", array_keys($fields)), 0, ["tags" => []]);
			}
			$fields["attributes"] = array_filter($fields["attributes"], function($value) {return is_numeric($value);});

		}
		// foreach ($fields as $field => $value)
		// {
		// 	if (!trim($value))
		// 	{
		// 		return TemplateEngine::renderAdminError(404, "Missing required field: {$field}");
		// 	}
		// 	$fields[$field] = trim($value);
		// }

		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToAdd . 's');
			$item = new $className(null, ...array_values($fields));
			$itemId = $repository->add($item);
			if ($entityToAdd === 'item')
			{
				if ($_FILES['image']['size'][0] !== 0)
				{
					$this->addBaseImages($_FILES, $itemId);
				}
			}
		}
		catch (ValidateException $e)
		{
			return TemplateEngine::renderAdminError(400, $e->getMessage());
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			return TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderSuccessAddPage();
	}

}