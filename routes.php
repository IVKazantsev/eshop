<?php

use N_ONE\Core\Routing\Router;

Router::get('/public/', function() {
	return (new N_ONE\App\Controller\CatalogueController())->render('cataloguePage', []);
});