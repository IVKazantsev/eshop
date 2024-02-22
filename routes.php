<?php

use N_ONE\App\Application;
use N_ONE\App\Model\Service\MiddleWare;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;

Router::get('/', function() {
	$di = Application::getDI();
	$currentTag = $_GET['tag'] ?? null;
	$currentSearchRequest = $_GET['SearchRequest'] ?? null;
	$currentPageNumber = (int)($_GET['page'] ?? null);
	$currentRange = $_GET['range'] ?? null;

	$tagsToFilter = $_GET['selectedTags'];
	$tagGroups = explode(';', $tagsToFilter);

	$finalTags = [];
	foreach ($tagGroups as $tagGroup)
	{

		[$parentId, $childIds] = explode(':[', trim($tagGroup, '[]'));
		$finalTags[$parentId] = array_map('trim', explode(',', $childIds));
	}

	var_dump($finalTags);
	$attributesToFilter = $_GET['attributes'];
	// var_dump($attributesToFilter);
	$attributeGroups = explode(';', $attributesToFilter);
	// var_dump($attributeGroups);
	foreach ($attributeGroups as $attributeGroup)
	{

		[$parentId, $childIds] = explode('=[', trim($attributeGroup, '[]'));
		[$from, $to] = explode('-', $childIds);
		$finalAttributes[$parentId]['from'] = $from;
		$finalAttributes[$parentId]['to'] = $to;
	}
	var_dump($finalAttributes);

	return ($di->getComponent('catalogController'))->renderCatalog(
		$currentPageNumber,
		$currentTag,
		$currentSearchRequest,
		$currentRange
	);
});

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
	$orderNumber = (int)($_GET['number'] ?? null);
	return ($di->getComponent('orderController'))->renderOrderInfoPage($orderNumber);
});

//роуты с защитой
Router::get('/admin', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderDashboard();
}));

Router::get('/admin/:string', MiddleWare::adminMiddleware(function(Route $route) {
	$di = Application::getDI();
	$entityToEdit = $route->getVariables()[0];
	$currentPageNumber = (int)($_GET['page'] ?? null);

	return ($di->getComponent('adminController'))->renderEntityPage($entityToEdit, $currentPageNumber);
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

Router::get('/admin/:entity/delete/:id', MiddleWare::adminMiddleware(function(Route $route) {
	$entityToDelete = $route->getVariables()[0];
	$entityId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderConfirmDeletePage($entityToDelete, $entityId);
}));

Router::post('/admin/:entity/delete/:id', function(Route $route) {
	$entityToDelete = $route->getVariables()[0];
	$entityId = (int)$route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->processDeletion($entityToDelete, $entityId);
});

Router::get('/admin/delete/success', MiddleWare::adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderSuccessDeletePage();
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