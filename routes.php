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
		else
		{
			return $action($route);
		}
	};
}

//роуты с защитой
Router::get('/admin', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderDashboard();
}));

Router::get('/admin/items', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderItemsPage();
}));
Router::get('/admin/tags', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderTagsPage();
}));
Router::get('/admin/orders', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderOrdersPage();
}));
Router::get('/admin/users', adminMiddleware(function() {
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderUsersPage();
}));
Router::get('/admin/items/edit/:id', adminMiddleware(function(Route $route) {
	$itemId = $route->getVariables()[0];
	$di = Application::getDI();

	return ($di->getComponent('adminController'))->renderEditPage($itemId);
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