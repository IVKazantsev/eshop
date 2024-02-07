<?php

namespace N_ONE\App\Controller;

use N_ONE\App\Controller\BaseController;
use N_ONE\App\Model\Repository\UserRepository;
use N_ONE\App\Model\User;

class AdminController extends BaseController
{
	protected UserRepository $userRepository;
	private User $user;

	public function login(string $email, ?string $password): void
	{
		$trimmedEmail = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
		$trimmedPassword = trim($password);
		$this->user = $this->userRepository->getByEmail($trimmedEmail);
		if ($trimmedPassword === $this->user->getPass())
		{
			session_start();
			ob_start();
			$_SESSION['user_id'] = $this->user->getId();
			header('Location: /admin');
			ob_end_flush();
			exit();
		}
		else
		{
			echo "incorrect password";
		}

	}

	public function renderDashboard()
	{
		session_start();
		$this->user = $this->userRepository->getById($_SESSION['user_id']);

		return $this->templateEngine->render('pages/adminDashboard', ["user" => $this->user]);
	}

	public function checkIfLoggedIn(): bool
	{
		session_start();

		return true;
	}
}