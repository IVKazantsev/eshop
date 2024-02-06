<?php

use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;
use N_ONE\App\Controller;

Router::get('/', function() {
	return (new Controller\CatalogController())->renderCatalog();
});

Router::get('/products/:id', function(Route $route) {
	$carId = $route->getVariables()[0];

	return (new Controller\DetailController())->renderDetailPage($carId);
});

Router::get('/products/:id/order', function(Route $route) {
	$carId = $route->getVariables()[0];

	return (new Controller\OrderController())->renderOrderPage($carId);
});

Router::post('/products/:id/order', function(Route $route) {
	$carId = $route->getVariables()[0];

	return (new Controller\OrderController())->processOrder($carId);
});

Router::get('/successOrder/:id', function(Route $route) {
	$orderId = $route->getVariables()[0];

	return (new Controller\OrderController())->renderSuccessOrderPage($orderId);
});