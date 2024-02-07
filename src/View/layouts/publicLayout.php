<?php

/**
 * @var array $cars
 * @var string $content
 * @var Tag[] $tags
 */

use N_ONE\App\Model\Tag;
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
		<div class="tags-container">
			<div class="tags-title">Каталог товаров</div>
			<ul class="tags">
				<?php foreach ($tags as $tag): ?>
					<li class="tag-item"><a class="tag-link" href=""><?= $tag->getTitle() ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div id="logo">
		<a class="logo-link" href="/"><img src="<?= $iconsPath . 'logo.svg' ?>" alt=""></a>
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