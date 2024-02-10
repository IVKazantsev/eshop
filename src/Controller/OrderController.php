<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\App\Model\User;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class OrderController extends BaseController
{

	public function renderOrderPage(string $carId): string
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

	public function processOrder(string $carId): string
	{
		$phone = trim(ValidationService::validatePhoneNumber($_POST['phone']));
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$address = trim($_POST['address']);
		if (!($phone) || !($name) || !($email) || !($address) || !(filter_var($email, FILTER_VALIDATE_EMAIL)))
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
			$phone = ValidationService::validatePhoneNumber($_POST['phone']);

			if (!$phone)
			{
				throw new Exception();
			}

			$user = $this->userRepository->getByNumber($phone);

			if (!$user)
			{
				$this->userRepository->add(
					new User(null, 2, $name, $email, "", $phone, $address)
				);

				$user = $this->userRepository->getByNumber($phone);
			}
			elseif ($name !== $user->getName() || $email !== $user->getEmail() || $address !== $user->getAddress())
			{
				$updatedUser = new User(null, $user->getRoleId(), $name, $email, $user->getPass(), $phone, $address);
				$updatedUser->setId($user->getId());
				$this->userRepository->update($updatedUser);
			}

			$order = new Order(null, 1, $user->getId(), $carId, 1, 'обработка', $car->getPrice());
			$order->generateNumber(time());
			$this->orderRepository->add($order);
		}
		catch (Exception)
		{
			http_response_code(404);

			return TemplateEngine::renderError(404, "Page not found");
		}

		return $this->renderSuccessOrderPage($order->getNumber());

	}

	public function renderSuccessOrderPage(string $orderNumber): string
	{
		if ($_SERVER['REQUEST_URI'] !== "/successOrder/$orderNumber")
		{
			Router::redirect("/successOrder/$orderNumber");
		}

		$successOrderPage = TemplateEngine::render(
			'pages/successOrderPage', ['orderNumber' => $orderNumber]
		);

		return $this->renderPublicView($successOrderPage);
	}
}