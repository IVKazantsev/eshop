<?php

use N_ONE\App\Application;
use N_ONE\Core\Routing\Route;
use N_ONE\Core\Routing\Router;
use N_ONE\App\Controller;




Router::get('/', function()
{
	$di = Application::getDI();
	return (new Controller\CatalogController(
		$di->getComponent('tagRepository'),
		$di->getComponent('imageRepository'),
		$di->getComponent('itemRepository'),
		$di->getComponent('userRepository'),
		$di->getComponent('orderRepository'))
	)->renderCatalog();
});

Router::get('/products/:id', function(Route $route)
{
	$carId = $route->getVariables()[0];
	$di = Application::getDI();
	return (new Controller\DetailController(
		$di->getComponent('tagRepository'),
		$di->getComponent('imageRepository'),
		$di->getComponent('itemRepository'),
		$di->getComponent('userRepository'),
		$di->getComponent('orderRepository')
	))->renderDetails($carId);
});