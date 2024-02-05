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