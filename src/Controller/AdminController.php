<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Model\Item;
use N_ONE\App\Model\Repository\UserRepository;
use N_ONE\App\Model\User;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class AdminController extends BaseController
{
	protected UserRepository $userRepository;

	public static function displayLoginError(): void
	{
		session_start();
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
		session_start();

		if (empty($trimmedEmail) || empty($trimmedPassword))
		{
			$_SESSION['login_error'] = 'Please enter both email and password.';
			Router::redirect('/login');
			exit();
		}
		try
		{
			$user = $this->userRepository->getByEmail($trimmedEmail);

		}
		catch (\RuntimeException)
		{

			$_SESSION['login_error'] = 'Incorrect email or password. Please try again.';
			Router::redirect('/login');
			exit(401);
		}

		if ($user->getRoleId() !== 1)
		{
			$_SESSION['login_error'] = 'Insufficient rights';
			Router::redirect('/login');
			exit(401);
		}

		if ($trimmedPassword !== $user->getPass())
		{
			$_SESSION['login_error'] = 'Incorrect email or password. Please try again.';
			Router::redirect('/login');
			exit(401);
		}
		ob_start();
		$_SESSION['user_id'] = $user->getId();
		Router::redirect('/admin');
		ob_end_flush();
		exit();
	}

	// public function renderEditPage(string $itemId): string
	// {
	// 	try
	// 	{
	// 		$item = $this->itemRepository->getById($itemId);
	//
	// 		$content = TemplateEngine::render('pages/adminEditPage', [
	// 			'item' => $item,
	// 		]);
	// 	}
	// 	catch (Exception)
	// 	{
	// 		$content = TemplateEngine::render('pages/errorPage', [
	// 			'errorCode' => ':(',
	// 			'errorMessage' => 'Что-то пошло не так',
	// 		]);
	// 	}
	//
	// 	return $this->renderAdminView($content);
	// }
	public function renderEditPage(string $entityToEdit, string $itemId): string
	{
		$repository = $this->repositoryFactory->createRepository($entityToEdit);
		try
		{
			$item = $repository->getById($itemId);

			$content = TemplateEngine::render('pages/adminEditPage', [
				'item' => $item,
			]);
		}
		catch (Exception)
		{
			$content = TemplateEngine::render('pages/errorPage', [
				'errorCode' => ':(',
				'errorMessage' => 'Что-то пошло не так',
			]);
		}

		return $this->renderAdminView($content);
	}

	//TODO изменить
	public function renderLoginPage(string $view, array $params): string
	{
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
			$content = TemplateEngine::render('pages/errorPage', [
				'errorCode' => ':(',
				'errorMessage' => 'Товары не найдены',
			]);
		}

		return $this->renderAdminView($content);
	}

	public function checkIfLoggedIn(): bool
	{
		session_start();
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
			$content = TemplateEngine::render('pages/adminItemsPage', [
				'items' => $items,
			]);
		}
		catch (\InvalidArgumentException)
		{
			$content = TemplateEngine::render('pages/errorPage', [
				'errorCode' => ':(',
				'errorMessage' => 'Что-то пошло не так',
			]);
		}
		catch (Exception)
		{
			$content = TemplateEngine::render('pages/errorPage', [
				'errorCode' => ':(',
				'errorMessage' => 'Что-то пошло не так',
			]);
		}

		return $this->renderAdminView($content);
	}

	public function updateItem(string $itemId): string
	{
		$title = trim($_POST['title']);
		$price = trim($_POST['price']);
		$description = trim($_POST['description']);
		$driveType = trim($_POST['drive-type']);
		$transmissionType = trim($_POST['transmission-type']);
		$fuelType = trim($_POST['fuel-type']);
		$engineType = trim($_POST['engine-type']);
		if (
			!($title)
			|| !($price)
			|| !($description)
			|| !($driveType)
			|| !($transmissionType)
			|| !($fuelType)
			|| !($engineType)
		)
		{
			return TemplateEngine::renderError(404, "Something went wrong");
		}

		try
		{
			$driveType = $this->tagRepository->getByTitle($driveType);
			$transmissionType = $this->tagRepository->getByTitle($transmissionType);
			$fuelType = $this->tagRepository->getByTitle($fuelType);
			$engineType = $this->tagRepository->getByTitle($engineType);
			$tags = [$driveType, $transmissionType, $fuelType, $engineType];
			$item = new Item($itemId, $title, 1, $price, $description, $tags, []);
			$this->itemRepository->update($item);
		}
		catch (Exception)
		{
			http_response_code(404);

			return TemplateEngine::renderError(404, "Page not found");
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

	public function renderConfirmDeletePage(string $itemId): string
	{
		try
		{
			$item = $this->itemRepository->getById($itemId);
		}
		catch (Exception)
		{
			http_response_code(404);
			echo TemplateEngine::renderError(404, "Page not found");
			exit;
		}

		$confirmDeletePage = TemplateEngine::render('pages/confirmDeletePage', [
			'item' => $item,
		]);

		return $this->renderAdminView($confirmDeletePage);
	}

	public function processDeletion(string $itemId)
	{
		if (!$itemId)
		{
			return TemplateEngine::renderError(404, "Something went wrong");
		}
		try
		{
			$this->itemRepository->delete($itemId);
		}
		catch (Exception)
		{
			http_response_code(404);
			echo TemplateEngine::renderError(404, "Page not found");
			exit;
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