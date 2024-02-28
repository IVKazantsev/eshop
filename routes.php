<?php

use N_ONE\App\Application;
use N_ONE\App\Model\Service\MiddleWare;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;

Router::get(
	'/',
	MiddleWare::processFilters(
		function(
			$route,
			$currentSearchRequest,
			$finalTags,
			$finalAttributes,
			$sortOrder
		) {
			$di = Application::getDI();
			$currentPageNumber = (int)($_GET['page'] ?? null);

			return ($di->getComponent('catalogController'))->renderCatalog(
				$currentPageNumber,
				$currentSearchRequest,
				$finalTags,
				$finalAttributes,
				$sortOrder
			);
		}
	)
);

Router::get('/products/:id', function(Route $route) {
	$carId = (int)$route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('detailController'))->renderDetailPage($carId);
});

Router::get('/products/:id/order', function(Route $route) {
	$carId = (int)$route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->renderOrderPage($carId);
});

Router::post('/processOrder', function() {
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->processOrder();
});

Router::post('/successOrder', function() {
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->renderSuccessOrderPage();
});

Router::get('/checkOrder', function() {
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->renderCheckOrderPage();
});

Router::get('/orderInfo', function() {
	$di = Application::getDI();
	$orderNumber = (int)($_GET['number'] ?? 0);
	$phoneNumber = (string)($_GET['phone'] ?? "");

	return ($di->getComponent('orderController'))->renderOrderInfoPage($phoneNumber, $orderNumber);
});

//роуты с защитой
Router::get('/admin', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderDashboard();
}));

Router::get('/admin/:entity', MiddleWare::adminMiddleware(function(Route $route) {
	$di = Application::getDI();
	$entityToEdit = $route->getVariables()[0];
	$currentPageNumber = (int)($_GET['page'] ?? null);
	$isActive = (int)($_GET['isActive'] ?? 1);

	return ($di->getComponent('adminController'))->renderEntityPage($entityToEdit, $currentPageNumber, $isActive);
}));

Router::get('/admin/:string/edit/:id', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToEdit = $route->getVariables()[0];
	$itemId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderEditPage($entityToEdit, $itemId);
}));

Router::post('/admin/:string/edit/:id', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToEdit = $route->getVariables()[0];
	$itemId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->updateEntity($entityToEdit, $itemId);
}));

Router::get('/admin/:string/add', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToEdit = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderAddPage($entityToEdit);
}));

Router::post('/admin/:string/add', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToAdd = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->addEntity($entityToAdd);
}));

Router::get('/admin/edit/success', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderSuccessEditPage();
}));

Router::get('/admin/add/success', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderSuccessAddPage();
}));

Router::get('/admin/:entity/restore/:id', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToDelete = $route->getVariables()[0];
	$entityId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderConfirmPage($entityToDelete, $entityId, 'восстановить');
}));

Router::post('/admin/:entity/restore/:id', function(Route $route) {
	$entityToDelete = $route->getVariables()[0];
	$entityId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->processChangeActive($entityToDelete, $entityId, 1);
});

Router::get('/admin/restore/success', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderSuccessPage(1);
}));

Router::get('/admin/:entity/delete/:id', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToDelete = $route->getVariables()[0];
	$entityId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderConfirmPage($entityToDelete, $entityId, 'удалить');
}));

Router::post('/admin/:entity/delete/:id', function(Route $route) {
	$entityToDelete = $route->getVariables()[0];
	$entityId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->processChangeActive($entityToDelete, $entityId, 0);
});

Router::get('/admin/delete/success', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderSuccessPage(0);
}));

//роуты доступные всем
Router::get('/login', function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderLoginPage('login', []);
});

Router::post('/login', function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->login($_POST['email'], $_POST['password']);
});

Router::get('/logout', function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->logout();
});

// роуты для картинок
// Router::get('/addImagesForm/:id', function(Route $route) {
// 	$di = Application::getDI();
// 	$itemId = $route->getVariables()[0];
//
// 	return ($di->getComponent('imageController'))->renderAddImagesForm($itemId);
// });
//
// Router::post('/addImages/:id', function(Route $route) {
// 	$di = Application::getDI();
// 	$itemId = $route->getVariables()[0];
//
// 	return ($di->getComponent('imageController'))->addBaseImages($_FILES, $itemId);
// });
//
// Router::get('/deleteImageForm/:id', function(Route $route) {
// 	$di = Application::getDI();
// 	$itemId = $route->getVariables()[0];
//
// 	return ($di->getComponent('imageController'))->renderDeleteImagesForm($itemId);
// });
//
// Router::post('/deleteImages', function() {
// 	$di = Application::getDI();
//
// 	return ($di->getComponent('imageController'))->deleteImages($_POST['imageIds']);
// });