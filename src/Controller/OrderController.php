<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\User;
use N_ONE\App\Service\ValidationService;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;
use N_ONE\App\Model\Repository;

class OrderController extends BaseController
{
	protected Repository\UserRepository $userRepository;
	protected Repository\OrderRepository $orderRepository;

	public function __construct()
	{
		parent::__construct();
		$dbConnection = DbConnector::getInstance();
		$this->userRepository = new Repository\UserRepository($dbConnection);
		$this->orderRepository = new Repository\OrderRepository(
			$dbConnection, $this->userRepository, $this->itemRepository
		);
	}

	public function renderOrderPage(int $carId): string
	{
		try
		{
			$car = $this->itemRepository->getById($carId);
		}
		catch (Exception)
		{
			http_response_code(404);
			echo TemplateEngine::renderError(404, "Page not found");
			exit;
		}

		$orderPage = TemplateEngine::render('pages/orderPage', [
			'car' => $car,
		]);

		return $this->renderPublicView($orderPage);
	}

	public function processOrder(int $carId): string
	{
		if (!($_POST['name']) || !($_POST['email']) || !($_POST['phone']) || !($_POST['address']))
		{
			return TemplateEngine::renderError(404, "Something went wrong");
		}

		try
		{
			$car = $this->itemRepository->getById($carId);
			if (!$car)
			{
				throw new Exception();
			}

			$user = $this->userRepository->getByNumber($_POST['phone']);

			$name = $_POST['name'];
			$email = $_POST['email'];

			$phone = ValidationService::validatePhoneNumber($_POST['phone']);

			$address = $_POST['address'];

			if (!$user)
			{
				$this->userRepository->add(
					new User(2, 'customer', $name, $email, "", $phone, $address)
				);

				$user = $this->userRepository->getByNumber($phone);
			}
			elseif ($name !== $user->getName() || $email !== $user->getEmail() || $address !== $user->getAddress())
			{
				$this->userRepository->update($user);
			}

			$this->orderRepository->add(new Order($user->getId(), $carId, 1, 'обработка', $car->getPrice()));
			$order = $this->orderRepository->getByUserAndItem($user->getId(), $carId);
		}
		catch (Exception)
		{
			http_response_code(404);

			return TemplateEngine::renderError(404, "Page not found");
		}

		return $this->renderSuccessOrderPage($order->getId());

	}

	public function renderSuccessOrderPage(int $orderId): string
	{
		if ($_SERVER['REQUEST_URI'] !== "/successOrder/$orderId")
		{
			Router::redirect("/successOrder/$orderId");
		}

		$successOrderPage = TemplateEngine::render(
			'pages/successOrderPage', ['orderId' => $orderId]
		);

		return $this->renderPublicView($successOrderPage);
	}
}