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
use ReflectionException;

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

	public function renderEditPage(string $entityToEdit, int $entityId): string
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
					$deleteImagesSection = TemplateEngine::render('components/deleteImagesSection', [
						'images' => $images[$entityId] ?? [],
					]);

					$additionalSections = [
						'tags' => $tagsSection,
						'attributes' => $attributesSection,
						'images' => $deleteImagesSection,
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
			// Не получилось создать репозиторий. Логирование не нужно
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
			$previousPageUri = PaginationService::getPreviousPageUri($pageNumber);
			$nextPageUri = PaginationService::getNextPageUri(count($entities), $pageNumber);

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

	/**
	 * @throws ValidateException
	 * @throws DatabaseException
	 */
	public function updateEntity(string $entityType, string $entityId): string
	{
		$fields = $_POST;
		$fields['id'] = $entityId;
		//Костыль на приведение названия типа сущности из URL к названию класса
		$className = 'N_ONE\App\Model\\' . ucfirst(substr_replace($entityType, '', -1));

		try
		{
			foreach ($fields as $field => $value)
			{
				$fields[$field] = ValidationService::validateEntryField($value);
			}
			if (array_key_exists('imageIds', $fields) && $entityType === 'items')
			{
				$this->deleteImages($fields['imageIds']);
			}
			if ($_FILES['image']['size'][0] !== 0 && $entityType === 'items')
			{
				$this->addBaseImages($_FILES, $entityId);
			}
			if ($_FILES['image']['size'][0] !== 0 && !$fields['parentId'] && $entityType === 'tags')
			{
				$this->addTagLogo($_FILES, $entityId);
			}
			$repository = $this->repositoryFactory->createRepository($entityType);
			$entity = $className::fromFields($fields);
			$repository->update($entity);
		}
		catch (InvalidArgumentException)
		{
			// Не получилось создать репозиторий. Логирование не нужно
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
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

	public function renderConfirmPage(string $entityType, int $entityId, string $action): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityType);
			$entity = $repository->getById($entityId);
			if ($entity === null)
			{
				$content = TemplateEngine::renderAdminError(':(', 'Данный товар не найден');
			}
			else
			{
				$entityName = substr($entityType, 0, -1);

				$content = TemplateEngine::render('pages/confirmPage', [
					'entity' => $entityName,
					'entityId' => $entityId,
					'action' => $action,
				]);
			}
		}
		catch (InvalidArgumentException)
		{
			// Не получилось создать репозиторий. Логирование не нужно
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);

			$content = TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);

			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderAdminView($content);
	}

	public function processChangeActive(string $entities, int $entityId, int $isActive): string
	{
		if (!$entityId)
		{
			return TemplateEngine::renderAdminError(404, "Страница не найдена");
		}
		try
		{
			$repository = $this->repositoryFactory->createRepository($entities);
			$repository->changeActive($entities, $entityId, $isActive);
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
			// Не получилось создать репозиторий. Логирование не нужно
			return TemplateEngine::renderAdminError(":(", "Что-то пошло не так");

		}

		return $this->renderSuccessPage($isActive);
	}

	public function renderSuccessPage(int $isActive): string
	{
		$currentPage = ($isActive === 0) ? 'delete' : 'restore';
		if ($_SERVER['REQUEST_URI'] !== "/admin/$currentPage/success")
		{
			Router::redirect("/admin/$currentPage/success");
		}

		$successDeletePage = TemplateEngine::render('pages/successPage', ['isActive' => $isActive]);

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

	/**
	 * @throws ValidateException
	 */
	public function addTagLogo($files, int $itemId): bool
	{
		ValidationService::validateImage($files);

		$targetDir = ROOT . '/public' . Configurator::option(
				'ICONS_PATH'
			); // директория для сохранения загруженных файлов
		$fileExtension = pathinfo($files['image']['name'][0], PATHINFO_EXTENSION);
		$finalPath = $targetDir . $itemId . ".$fileExtension";

		if (file_exists($finalPath))// Проверяем, существует ли файл
		{
			unlink($finalPath); // Удаляем существующий файл
		}

		if (move_uploaded_file($files["image"]["tmp_name"][0], $finalPath))// Сохраняем файл по указанному пути
		{
			return true;// Файл успешно сохранен
		}

		return false;// Произошла ошибка при сохранении файла
	}

	public function renderAddPage(string $entityToAdd): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToAdd . 's');
			$className = 'N_ONE\App\Model\\' . ucfirst(
					$entityToAdd
				);//Костыль на приведение названия типа сущности из URL к названию класса

			$entity = ($className)::createDummyObject();
			switch (get_class($entity))
			{
				case Item::class:
				{
					$parentTags = $this->tagRepository->getParentTags();
					$attributes = $this->attributeRepository->getList();

					$itemTags = [];
					$childrenTags = [];
					$specificFields = [
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
						'components/deleteImagesSection', []
					);

					$additionalSections = [
						'tags' => $tagsSection,
						'attributes' => $attributesSection,
						'images' => $deleteImagesSection,
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
		catch (InvalidArgumentException)
		{
			// Не получилось создать репозиторий. Логирование не нужно
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
		}
		catch (ReflectionException)
		{
			Logger::error("Failed to use Reflection", __METHOD__);
			$content = TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			$content = TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderAdminView($content);
	}

	public function addEntity(string $entityToAdd): string
	{
		$fields = $_POST;
		$className = 'N_ONE\App\Model\\' . ucfirst($entityToAdd); //Костыль на приведение названия типа сущности из URL к названию класса

		try
		{
			foreach ($fields as $field => $value)
			{
				$fields[$field] = ValidationService::validateEntryField($value);
			}
			$repository = $this->repositoryFactory->createRepository($entityToAdd . 's');
			$item = $className::fromFields($fields);
			$itemId = $repository->add($item);
			if ($entityToAdd === 'item' && $_FILES['image']['size'][0] !== 0)
			{
				$this->addBaseImages($_FILES, $itemId);
			}
			if ($entityToAdd === 'tag' && $_FILES['image']['size'][0] !== 0 && !$fields['parentId'])
			{
				$this->addTagLogo($_FILES, $itemId);
			}
		}
		catch (InvalidArgumentException)
		{
			// Не получилось создать репозиторий. Логирование не нужно
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');
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