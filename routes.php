<?php

use N_ONE\App\Application;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;


Router::get('/', function()
{
	$di = Application::getDI();
	return ($di->getComponent('catalogController'))->renderCatalog();
});

Router::get('/products/:id', function(Route $route)
{
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