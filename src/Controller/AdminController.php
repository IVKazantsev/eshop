<?php

namespace N_ONE\App\Controller;

use Exception;
use InvalidArgumentException;
use N_ONE\App\Model\Item;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Exceptions\LoginException;
use N_ONE\Core\Exceptions\ValidateException;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Repository\UserRepository;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\User;
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
		catch (DatabaseException|LoginException|Exception)
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

	public function renderEditPage(string $entityToEdit, string $itemId): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToEdit);
			$item = $repository->getById($itemId);
			if ($item === null)
			{
				$content = TemplateEngine::renderAdminError(':(', 'Данный товар не найден');

				return $this->renderAdminView($content);
			}
			switch (get_class($item))
			{
				case Item::class:
				{
					$parentTags = $this->tagRepository->getParentTags();
					$itemTags = [];
					$childrenTags = [];
					foreach ($parentTags as $parentTag)
					{
						$childrenTags[(string)($parentTag->getTitle())] = $this->tagRepository->getByParentId(
							$parentTag->getId()
						);

					}
					foreach ($item->getTags() as $tag)
					{
						$itemTags[$tag->getParentId()] = $tag->getId();

					}
					$content = TemplateEngine::render('pages/adminEditPage', [
						'item' => $item,
						'childrenTags' => $childrenTags,
						'parentTags' => $parentTags,
						'itemTags' => $itemTags,
					]);
					break;
				}
				case Tag::class:
				{
					$parentTags = $repository->getParentTags();
					$content = TemplateEngine::render('pages/adminEditPage', [
						'item' => $item,
						'parentTags' => $parentTags,
					]);
					break;

				}
				case User::class:
				{
					$content = TemplateEngine::render('pages/adminEditPage', [
						'item' => $item,
					]);
					break;

				}
				case Order::class:
				{
					$statuses = $this->orderRepository->getStatuses();
					$content = TemplateEngine::render('pages/adminEditPage', [
						'item' => $item,
						'statuses' => $statuses,
					]);
					break;

				}
				default:
				{
					$content = TemplateEngine::render('pages/adminEditPage', []);
					break;

				}
			}
		}
		catch (InvalidArgumentException|Exception)
		{
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

	public function renderEntityPage(string $entityToDisplay): string
	{
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityToDisplay);
			$items = $repository->getList();
			if (empty($items))
			{
				$content = TemplateEngine::renderAdminError(':(', 'Товары не найдены');

				return $this->renderAdminView($content);
			}
			$content = TemplateEngine::render('pages/adminItemsPage', [
				'items' => $items,
			]);
		}
		catch (InvalidArgumentException)
		{
			$content = TemplateEngine::renderAdminError('404', 'Страница не найдена');
		}
		catch (Exception)
		{
			$content = TemplateEngine::renderAdminError(':(', 'Что-то пошло не так');

		}

		return $this->renderAdminView($content);
	}

	public function updateItem(string $entityType, string $itemId): string
	{
		$fields = $_POST;
		foreach ($fields as $field){
			$fields[$field] = ValidationService::validateEntryField($field);
		}
		$className = 'N_ONE\App\Model\\' . ucfirst(
				substr_replace($entityType, '', -1)
			); //Костыль на приведение названия типа сущности из URL к названию класса
		if ($entityType === 'tags')
		{
			foreach ($fields as $field => $value)
			{
				if ($field === 'value' && $value === '')
				{
					$fields[$field] = 'null';
				}
			}
		}
		if ($entityType === 'items')
		{
			$tags = [];

			foreach ($fields as $field => $value)
			{
				if (is_numeric($field))
				{
					$tags[] = new Tag($value, '', $field, null);
					unset($fields[$field]);
				}
				if (
					($field === 'isActive' || $field === 'sortOrder')
					&& $value === '0'
				) //РАЗРЕШЕНИЕ НА ИСПОЛЬЗОВАНИЕ FALSY ДЛЯ УКАЗАННЫХ ПОЛЕЙ
				{
					continue;
				}
			}

			$fields['tags'] = $tags;
			$fields['images'] = [];
		}
		foreach ($fields as $field => $value)
		{
			if (!trim($value))
			{
				return TemplateEngine::renderError(404, "Missing required field: {$field}");
			}
			$fields[$field] = trim($value);
		}
		try
		{
			$repository = $this->repositoryFactory->createRepository($entityType);
			$item = new $className($itemId, ...array_values($fields));
			$repository->update($item);
		}
		catch (ValidateException $e)
		{
			return TemplateEngine::renderAdminError(400, $e->getMessage());
		}
		catch (DatabaseException)
		{
			return TemplateEngine::renderAdminError(";(", "Что-то пошло не так");
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
		catch (DatabaseException|InvalidArgumentException)
		{
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
}