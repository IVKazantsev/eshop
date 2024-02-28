<?php

namespace N_ONE\App\Controller;

use mysqli_sql_exception;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\App\Model\User;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;
use N_ONE\Core\Exceptions\NotFoundException;
use N_ONE\Core\Exceptions\ValidateException;
use N_ONE\Core\Log\Logger;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class OrderController extends BaseController
{
	public function renderOrderPage(string $itemId): string
	{
		try
		{
			$item = $this->itemRepository->getById($itemId, 21);

			$content = TemplateEngine::render('pages/orderPage', [
				'item' => $item,
			]);
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);
			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);
			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderPublicView($content);
	}

	public function processOrder(): string
	{
		try
		{
			$itemId = (int)ValidationService::validateEntryField($_POST['itemId']);
			$phone = ValidationService::validatePhoneNumber($_POST['phone']);
			$name = ValidationService::validateEntryField($_POST['name']);
			$email = ValidationService::validateEmailAddress($_POST['email']);
			$address = ValidationService::validateEntryField($_POST['address']);

			$item = $this->itemRepository->getById($itemId, true);
			if (!$item)
			{
				throw new NotFoundException("There is no item with id $itemId");
			}
			$user = $this->userRepository->getByNumber($phone);

			if (!$user)
			{
				$user = new User(null, 2, $name, $email, "", $phone, $address);
				$this->userRepository->add($user);
				$user = $this->userRepository->getByNumber($phone);
			}
			elseif ($name !== $user->getName() || $email !== $user->getEmail() || $address !== $user->getAddress())
			{
				$updatedUser = new User(null, $user->getRoleId(), $name, $email, $user->getPass(), $phone, $address);
				$updatedUser->setId($user->getId());
				$this->userRepository->update($updatedUser);
			}
			$hashAlgo = Configurator::option('ORDER_HASH_ALGO');
			$hashPrefix = Configurator::option('ORDER_HASH_PREFIX');
			$userId = $user->getId();
			$time = time();
			$orderNumber = hash($hashAlgo, "$hashPrefix $userId $itemId $time");
			$order = new Order(null, $user->getId(), $itemId, 1, 'обработка', $item->getPrice(), $orderNumber);
			$this->orderRepository->add($order);

			$content = TemplateEngine::render(
				'pages/processOrder', ['orderNumber' => $orderNumber]
			);
		}
		catch (ValidateException $e)
		{
			$content = TemplateEngine::renderPublicError(400, $e->getMessage());
		}
		catch (NotFoundException)
		{
			$content = TemplateEngine::renderPublicError(404, "Страница не найдена");
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);

			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);

			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderPublicView($content);

	}

	public function renderSuccessOrderPage(): string
	{
		try
		{
			$orderNumber = ValidationService::validateEntryField($_POST["orderNumber"]);
			$successOrderPage = TemplateEngine::render(
				'pages/successOrderPage', ['orderNumber' => $orderNumber]
			);

			return $this->renderPublicView($successOrderPage);
		}
		catch (ValidateException)
		{
			return TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}
	}

	public function renderCheckOrderPage(): string
	{
		$checkOrderPage = TemplateEngine::render('pages/checkOrderPage');

		return $this->renderPublicView($checkOrderPage);
	}

	public function renderOrderInfoPage(?string $phoneNumber, ?int $orderNumber): string
	{
		try
		{
			$phoneNumber = ValidationService::validatePhoneNumber($phoneNumber);
			$user = $this->userRepository->getByNumber($phoneNumber);
			if (!$user || !$orderNumber)
			{
				$content = TemplateEngine::renderPublicError(";(", "Заказ не найден");

				return $this->renderPublicView($content);
			}

			$order = $this->orderRepository->getByNumber($orderNumber, true);
			if ($order === null)
			{
				$content = TemplateEngine::renderPublicError(";(", "Заказ не найден");

				return $this->renderPublicView($content);
			}

			$item = $this->itemRepository->getById($order->getItemId(), true);

			$content = TemplateEngine::render('pages/orderInfoPage', [
				'order' => $order,
				'item' => $item,
			]);
		}
		catch (ValidateException $e)
		{
			$content = TemplateEngine::renderPublicError(400, $e->getMessage());
		}
		catch (DatabaseException)
		{
			Logger::error("Failed to fetch data from repository", __METHOD__);

			$content = TemplateEngine::renderPublicError(":(", "Что-то пошло не так");
		}
		catch (mysqli_sql_exception)
		{
			Logger::error("Failed to run query", __METHOD__);

			$content = TemplateEngine::renderPublicError(";(", "Что-то пошло не так");
		}

		return $this->renderPublicView($content);
	}
}