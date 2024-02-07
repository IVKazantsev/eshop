<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Model\Repository\UserRepository;
use N_ONE\App\Model\User;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class AdminController extends BaseController
{
	protected UserRepository $userRepository;
	private User $user;

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

		if (empty($trimmedEmail) || empty($trimmedPassword))
		{
			session_start();
			$_SESSION['login_error'] = 'Please enter both email and password.';
			Router::redirect('/login');
			exit();
		}
		try
		{
			$this->user = $this->userRepository->getByEmail($trimmedEmail);

		}
		catch (\RuntimeException)
		{
			session_start();

			$_SESSION['login_error'] = 'Incorrect email or password. Please try again.';
			Router::redirect('/login');
			exit();
		}

		if ($trimmedPassword === $this->user->getPass())
		{
			session_start();
			ob_start();
			$_SESSION['user_id'] = $this->user->getId();
			Router::redirect('/admin');
			ob_end_flush();
			exit();
		}
		else
		{
			$_SESSION['login_error'] = 'Incorrect email or password. Please try again.';
			Router::redirect('/login');
			exit();
		}
	}

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

	public function renderItemsPage(): string
	{

		try
		{
			$items = $this->itemRepository->getList();

			$content = TemplateEngine::render('pages/adminItemsPage', [
				'items' => $items,
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
}