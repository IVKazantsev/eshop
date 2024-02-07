<?php

use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;
use N_ONE\App\Controller;

Router::get('/', function() {
	return (new Controller\CatalogueController())->renderCatalogue();
});

Router::get('/products/:id', function(Route $route) {
	$carId = $route->getVariables()[0];

	return (new Controller\DetailsController())->renderDetails($carId);
});

Router::get('/login', function() {
	return (new Controller\AdminController())->render('login', []);
});
Router::post('/login', function() {
	return (new Controller\AdminController())->login($_POST['email'], $_POST['password']);
});
Router::get('/admin', function() {
	return (new Controller\AdminController())->renderDashboard();
});