<?php

use N_ONE\Core\Routing\Router;
use N_ONE\Core\TemplateEngine\TemplateEngine;

Router::get('/', function() {
	return (new N_ONE\App\Controller\CatalogueController())->renderLayout(
		[
			'content' => (new TemplateEngine(ROOT . '/src/View/'))->render('cataloguePage', [
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
			]),
		]
	);
});
Router::get('/products/:id', function() {
	return (new N_ONE\App\Controller\CatalogueController())->renderLayout([
																			  'content' => (new TemplateEngine(
																				  ROOT . '/src/View/'
																			  ))->render('detailsPage', [
																				  'car' => [

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
																			  ]),
																		  ]);
});