<?php

namespace N_ONE\App\Model\Service;

use Closure;
use N_ONE\App\Application;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;

class MiddleWare
{
	public static function adminMiddleware(callable $action): Closure
	{
		return static function(Route $route) use ($action) {
			$di = Application::getDI();
			$userRepo = $di->getComponent('userRepository');
			session_start();
			if (!isset($_SESSION['user_id'])) //Проверяем есть ли сессия с пользователем
			{
				if (isset($_COOKIE['remember_me'])) //проверяем есть ли кука с токеном
				{
					$userId = $userRepo->getUserByToken($_COOKIE['remember_me']); //Ищем пользователя по токену
					if ($userId) //Пользователь есть, начинаем/продляем сессию
					{
						$_SESSION['user_id'] = $userId;
					}
				}
				if (!isset($_SESSION['user_id'])) //Пользователя нет, перенаправляем на логин
				{
					Router::redirect('/login');
					exit();
				}
			}

			return $action($route);
		};
	}
	// public static function adminMiddleware(callable $action): Closure
	// {
	// 	return static function(Route $route) use ($action) {
	// 		session_start();
	// 		if (!isset($_SESSION['user_id']))
	// 		{
	// 			Router::redirect('/login');
	// 			exit();
	// 		}
	//
	// 		return $action($route);
	// 	};
	// }

	public static function processFilters(callable $action): Closure
	{
		return static function(Route $route) use ($action) {
			$tagsToFilter = $_GET['selectedTags'];
			$tagGroups = explode(';', $tagsToFilter);

			$finalTags = [];
			foreach ($tagGroups as $tagGroup)
			{
				[$parentId, $childIds] = explode(':[', trim($tagGroup, '[]'));
				foreach (explode(',', $childIds) as $childId)
				{
					$finalTags[(int)$parentId][] = (int)trim($childId);
				}
			}

			$attributesToFilter = $_GET['attributes'];
			$attributeGroups = explode(';', $attributesToFilter);
			$finalAttributes = [];

			foreach ($attributeGroups as $attributeGroup)
			{
				[$parentId, $childIds] = explode('=[', trim($attributeGroup, '[]'));
				[$from, $to] = explode('-', $childIds);
				$finalAttributes[(int)$parentId]['from'] = (int)$from;
				$finalAttributes[(int)$parentId]['to'] = (int)$to;
			}

			$sortField = $_GET['sortOrder'] ?? null;
			[$attributeId, $sortDirection] = explode('-', $sortField);
			$sortOrder = ['column' => $attributeId, 'direction' => $sortDirection];
			$currentSearchRequest = $_GET['searchRequest'] ?? null;
			unset($finalTags[0], $finalAttributes[0]);

			return $action(
				$route,
				$currentSearchRequest,
				$finalTags,
				$finalAttributes,
				$sortOrder,
			);
		};
	}
}