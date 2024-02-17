<?php

/**
 * @var array $cars
 * @var string $content
 * @var Tag[] $tags
 * @var Attribute[] $attributes
 * @var string $currentSearchRequest
 */

use N_ONE\App\Model\Service\ValidationService;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Attribute;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');
//TODO СДЕЛАТЬ ПОДКЛЮЧЕНИЕ ДОП ФАЙЛОВ CSS В ЗАВИСИМОСТИ ОТ СТРАНИЦЫ
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/styles/reset.css">
	<link rel="stylesheet" href="/styles/style.css">

	<?php if (isset($additional_css)): ?>
		<link rel="stylesheet" href="<?= $additional_css ?>">
	<?php endif; ?>

	<title>eshop</title>
</head>
<body>
<div class="container">
	<div class="sidebar">
		<div class="tags-container">
			<div class="tags-title">КАТЕГОРИИ</div>

			<ul class="tags">
				<?php foreach ($tags[""] as $parentTag): ?>
					<li class="tag-item">
						<?= $parentTag->getTitle() ?>
					</li>
					<ul class="child-tags">
						<?php foreach ($tags[$parentTag->getId()] as $childTag): ?>
							<li class="tag-item">
								<a
									class="tag-link"
									href="<?= '/?tag=' . $childTag->getTitle() ?>"
								>
									<?= $childTag->getTitle() ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
				<?php foreach ($attributes as $attribute): ?>
					<li class="tag-item">
						<?= $attribute->getTitle() ?>
					</li>
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
				<form class="search-form" action="/" method="get">
					<div class="search-icon-and-input">
						<input name="SearchRequest" type="text" placeholder="Поиск" value="<?= ValidationService::safe($currentSearchRequest ?? '')?>" class="search-input">
					</div>
					<button type="submit" class="search-button btn"><img class="search-icon" src="<?= $iconsPath ?>search.svg" alt="search-icon"/></button>
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