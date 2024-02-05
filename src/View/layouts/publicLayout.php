<?php

/**
 * @var array $cars
 * @var string $content
 */

use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/styles/style.css">
	<title>eshop</title>
</head>
<body>
<div class="container">
	<div class="sidebar">
		<div id="logo">
			<a href="/"><img src="<?= $iconsPath . 'logo.svg' ?>" alt=""></a>
		</div>
		<ul class="tags">
			<li class="tag-item"><a class="tag-link" href="/products/1">Машина №1</a></li>
			<li class="tag-item"><a class="tag-link" href="/products/2">Машина №2</a></li>
			<li class="tag-item"><a class="tag-link" href="/products/3">Машина №3</a></li>
			<li class="tag-item"><a class="tag-link" href="/products/4">Машина №4</a></li>
		</ul>
	</div>
	<header>
		<div class="bar">

			<div class="searchbar">
				<form class="search-form" action="" method="get">
					<div class="search-icon-and-input">
						<img src="<?= $iconsPath ?>search.svg" alt="search-icon"/>
						<input type="text" placeholder="Поиск" class="search-input">
					</div>
					<button type="submit" class="search-button btn">Найти</button>
				</form>
				<button type="submit" class="check-order-button btn">Проверить заказ</button>
			</div>
		</div>
	</header>

	<main>
		<?= $content ?>
	</main>

	<footer>Created by N_ONE team 2024</footer>
</div>
</body>
</html>