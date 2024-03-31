<?php

namespace N_ONE\App\Controller;

use mysqli_sql_exception;
use N_ONE\App\Controller\BaseController;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Exceptions\LoginException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class AdminLoginController extends BaseController
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

	public function login(string $email, ?string $password, ?bool $rememberMe = false): void
	{
		$trimmedEmail = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
		$trimmedPassword = trim($password);
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
			setcookie(session_name(), session_id(), time() + 1800); //Сгорание сессии через 30 минут после начала

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

			if (!password_verify($trimmedPassword, $user->getPass()))
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

		if ($rememberMe)
		{
			$token = bin2hex(random_bytes(32));
			$this->userRepository->addToken($user->getId(), $token);
			setcookie('remember_me', $token, time() + (86400), "/");
		}
		$_SESSION['user_id'] = $user->getId();
		ob_start();
		Router::redirect('/admin/items');
		ob_end_flush();
		exit();
	}

	public function renderLoginPage(string $view, array $params): string
	{
		static::displayLoginError();

		return TemplateEngine::render("pages/$view", $params);
	}

	public function logout(): void
	{
		session_start();
		session_unset();
		session_destroy();

		setcookie('remember_me', '', time() - 3600, '/'); // Set the cookie's expiration to a time in the past

		Router::redirect('/login');
		exit();
	}

}