<?php

use N_ONE\App\Application;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;
use N_ONE\App\Controller;

Router::get('/', function() {
	$di = Application::getDI();
	$currentTag = $_GET['tag'] ?? null;
	$currentSearchRequest = $_GET['SearchRequest'] ?? null;
	$currentPageNumber = $_GET['page'] ?? null;

	return ($di->getComponent('catalogController'))->renderCatalog(
		$currentPageNumber,
		$currentTag,
		$currentSearchRequest
	);
});

Router::get('/products/:id', function(Route $route) {
	$carId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('detailController'))->renderDetailPage($carId);
});

Router::get('/products/:id/order', function(Route $route) {
	$carId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->renderOrderPage($carId);
});

Router::post('/products/:id/order', function(Route $route) {
	$carId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->processOrder($carId);
});

Router::get('/successOrder/:id', function(Route $route) {
	$orderId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('orderController'))->renderSuccessOrderPage($orderId);
});

// TODO отрефакторить middleware куда-нибудь
function adminMiddleware(callable $action)
{
	return function(Route $route) use ($action) {
		session_start();
		if (!isset($_SESSION['user_id']))
		{
			Router::redirect('/login');
			exit();
		}

		return $action($route);
	};
}

//роуты с защитой
Router::get('/admin', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderDashboard();
}));

Router::get('/admin/:string', adminMiddleware(function(Route $route) {
	$di = Application::getDI();
	$entityToEdit = $route->getVariables()[0];

	return ($di->getComponent('adminController'))->renderEntityPage($entityToEdit);
}));

Router::get('/admin/:string/edit/:id', adminMiddleware(function(Route $route) {
	$entityToEdit = $route->getVariables()[0];
	$itemId = $route->getVariables()[1];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderEditPage($entityToEdit, $itemId);
}));

Router::post('/admin/items/edit/:id', adminMiddleware(function(Route $route) {
	$itemId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->updateItem($itemId);
}));

Router::get('/admin/edit/success', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderSuccessEditPage();
}));

Router::get('/admin/items/delete/:id', adminMiddleware(function(Route $route) {
	$itemId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderConfirmDeletePage($itemId);
}));

Router::post('/admin/items/delete/:id', function(Route $route) {
	$itemId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->processDeletion($itemId);
});

Router::get('/admin/delete/success', adminMiddleware(function() {
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