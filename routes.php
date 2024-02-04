<?php

use N_ONE\Core\Routing\Router;

Router::get('/', static function() {
	return (new N_ONE\App\Controller\CatalogueController())->renderView('cataloguePage', [
		'cars' => [
			[
				'id' => 1,
				'name' => 'MINI Cooper Countryman',
				'year' => '2012',
				'horsePower' => 122,
				'fuelType' => 'Бензин',
				'gearbox' => 'МКПП',
				'driveType' => 'Передний',
				'mileage' => 193200,
				'price' => 850000,
				'mainImage' => '1_1200_900_fullsize_main.jpeg',
			],
			[
				'id' => 2,
				'name' => 'MINI Bruh Countryman',
				'year' => '2012',
				'horsePower' => 122,
				'fuelType' => 'Бензин',
				'gearbox' => 'МКПП',
				'driveType' => 'Передний',
				'mileage' => 193200,
				'price' => 850000,
				'mainImage' => '782.jpg',
			],
		],
	]);
});
Router::get('/car/:id', static function() {
	return (new N_ONE\App\Controller\DetailsController())->renderView('detailsPage', ['car' => ['id' => 2]]);
});