<?php

use N_ONE\Core\Routing\Router;

Router::get('/', function() {
	return (new N_ONE\App\Controller\CatalogueController())->renderView('cataloguePage', []);
});