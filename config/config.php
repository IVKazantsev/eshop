<?php

return [
	'APP_NAME' => 'BitCar',
	'MENU' => [
		[
			'url' => '/index.php',
			'text' => 'главная',
			'selected' => false
		],
		[
			'url' => '/favorite.php',
			'text' => 'избранное',
			'selected' => false
		]
	],
	'DB_OPTIONS' => [],
	'ASSETS_PATH' =>'/resources/assets/',
	'POSTERS_PATH' => '/resources/moviePosters/',
	'NUM_OF_ITEMS_PER_PAGE' => 6,
	'MIGRATION_PATH' => "/core/Migration/migrations",
];