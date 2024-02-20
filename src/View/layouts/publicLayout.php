<?php

/**
 * @var array $items
 * @var string $content
 * @var Tag[] $tags
 * @var Attribute[] $attributes
 * @var string $currentSearchRequest
 */

use N_ONE\App\Model\Service\ValidationService;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Attribute;
use N_ONE\Core\Configurator\Configurator;

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
				<?php if (isset($tags[""])): ?>
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
				<?php endif; ?>

				<?php foreach ($attributes as $attribute): ?>
					<li class="tag-item">
						<?= $attribute->getTitle() ?>
					</li>
					<li class="tag-item">
						<input class="range_input" id="input1_<?=$attribute->getId()?>" type="number" min="0" max="999">
						<input class="range_input" id="input2_<?=$attribute->getId()?>" type="number" min="0" max="999">
						<button class="range_button" onclick="sendGetRequest(<?=$attribute->getId()?>)">sort</button>
					</li>
				<?php endforeach; ?>

				<script>
					function sendGetRequest(id) {
						var input1Value = document.getElementById('input1_' + id).value;
						var input2Value = document.getElementById('input2_' + id).value;

						// Формируем GET-запрос с использованием переменной из PHP
						// Выполняем GET-запрос
						window.location.href = `?range=${id}:${input1Value},${input2Value}`;
					}
				</script>

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
						<input name="SearchRequest" type="text" placeholder="Поиск" value="<?= ValidationService::safe($currentSearchRequest ?? '')?>" class="search-input" required>
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