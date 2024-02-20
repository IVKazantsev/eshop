<?php

namespace N_ONE\App\Controller;

use Exception;
use http\Exception\InvalidArgumentException;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\App\Model\User;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Exceptions\ValidateException;
use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class OrderController extends BaseController
{

	public function renderOrderPage(string $itemId): string
	{
		try
		{
			$item = $this->itemRepository->getById($itemId);
		}
		catch (Exception)
		{
			http_response_code(404);
			echo TemplateEngine::renderPublicError(404, "Page not found");
			exit;
		}

		$orderPage = TemplateEngine::render('pages/orderPage', [
			'item' => $item,
		]);

		return $this->renderPublicView($orderPage);
	}

	public function processOrder(string $itemId): string
	{
		try
		{
			$phone = ValidationService::validatePhoneNumber($_POST['phone']);
			$name = ValidationService::validateEntryField($_POST['name']);
			$email = ValidationService::validateEmailAddress($_POST['email']);
			$address = ValidationService::validateEntryField($_POST['address']);

			$item = $this->itemRepository->getById($itemId);
			if (!$item)
			{
				throw new InvalidArgumentException("There is no item with id $itemId");
			}
			$user = $this->userRepository->getByNumber($phone);

			if (!$user)
			{
				$user = new User(null, 2, $name, $email, "", $phone, $address);
				$user->setId($this->userRepository->add($user));
			}
			elseif ($name !== $user->getName() || $email !== $user->getEmail() || $address !== $user->getAddress())
			{
				$updatedUser = new User(null, $user->getRoleId(), $name, $email, $user->getPass(), $phone, $address);
				$updatedUser->setId($user->getId());
				$this->userRepository->update($updatedUser);
			}

			$order = new Order(null, $user->getId(), $itemId, 1, 'обработка', $item->getPrice());
			$orderId = $this->orderRepository->add($order);
		}
		catch (ValidateException $e)
		{
			return TemplateEngine::renderPublicError(400, $e->getMessage());
		}
		catch (InvalidArgumentException)
		{
			return TemplateEngine::renderPublicError(404, "Страница не найдена");
		}
		catch (DatabaseException)
		{
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderSuccessOrderPage($orderId);

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