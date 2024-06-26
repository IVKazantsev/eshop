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
$cssFile = isset($content) ? ValidationService::validateMetaTag($content, 'css') : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/styles/reset.css">
	<link rel="stylesheet" href="/styles/publicLayout.css">
	<?php if (isset($cssFile)): ?>
		<link rel="stylesheet" href="<?= $cssFile ?>">
	<?php endif; ?>
	<title>eshop</title>
</head>
<body>
<div class="container">
	<div class="menu-button-container">
		<button class="burger-button">
			<span></span>
		</button>
	</div>
	<div class="sidebar">
		<div class="tags-container">
			<a id="phone-home-link" href="/">Домой</a>
			<a id="phone-check-order-link" href="/checkOrder">Проверить заказ</a>
			<div class="tags-title">Категории</div>
			<ul class="tags">
				<?php if (isset($tags[""])): ?>
					<?php foreach ($tags[""] as $parentTag): ?>
						<li class="tag-item dropdown" onclick="toggleDropdown(event)">
							<a class="dropdown-toggle" href="#" data-parent-id="<?= $parentTag->getId() ?>">
								<?= ValidationService::safe($parentTag->getTitle()) ?>
								<i class="Chevron dropdown-icon chevron-up " id="chevron-<?= $parentTag->getId(
								) ?>"></i>
							</a>
							<div class="dropdown-content" id="dropdown-content-<?= $parentTag->getId() ?>">
								<?php foreach ($tags[$parentTag->getId()] as $childTag): ?>
									<label for="input-<?= $childTag->getId() ?>">
										<input
											type="checkbox"
											class="tag-checkbox"
											data-parent-id="<?= $parentTag->getId() ?>"
											value="<?= $childTag->getId() ?>"
											id="input-<?= $childTag->getId() ?>">
										<?= ValidationService::safe($childTag->getTitle()) ?>
									</label>
								<?php endforeach; ?>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php foreach ($attributes as $attribute): ?>
					<li class="tag-item dropdown" onclick="toggleDropdown(event)">
						<a class="dropdown-toggle" href="#" data-parent-id="<?= $attribute->getId() ?>">
							<?= ValidationService::safe($attribute->getTitle()) ?>
							<i class="Chevron dropdown-icon chevron-up " id="chevron-<?= $attribute->getId() ?>"></i>
						</a>
						<div class="dropdown-content attributes" id="dropdown-content-<?= $attribute->getId() ?>">
							<input
								class="range_input"
								id="input1_<?= $attribute->getId() ?>"
								type="number"
								min="0"
								max="999"
								name=""
								placeholder="От..."
								data-attribute-title="<?= ValidationService::safe($attribute->getTitle()) ?>">-&nbsp;
							<input
								class="range_input"
								id="input2_<?= $attribute->getId() ?>"
								type="number"
								min="0"
								max="999"
								placeholder="До..."
								data-attribute-title="<?= ValidationService::safe($attribute->getTitle()) ?>">
						</div>

					</li>
				<?php endforeach; ?>


			</ul>
			<button id="collect-data-btn">Фильтровать</button>
		</div>
	</div>
	<div id="logo">
		<a class="logo-link" href="/"><img src="<?= $iconsPath . 'logo.svg' ?>" alt=""></a>
	</div>
	<header>
		<div class="searchbar">
			<form id="search-form" method="get">
				<div class="search-icon-and-input">
					<input
						name="searchRequest"
						type="text"
						placeholder="Поиск"
						value="<?= ValidationService::safe($currentSearchRequest ?? '') ?>"
						id="search-input"
						required
					>
				</div>
				<button type="submit" class="btn" id="search-button">
					<img class="search-icon" src="<?= $iconsPath ?>search.svg" alt="search-icon"/></button>
			</form>
		</div>
		<a class="check-order-link" href="/checkOrder">Проверить заказ</a>
	</header>

	<main>
		<?= $content ?>
	</main>

	<footer>
		Created by N_ONE team 2024
	</footer>
</div>

<script>
	const tagsData = <?php echo json_encode($tags); ?>;
</script>
<script src="/js/categoryFilter.js"></script>
<script src="/js/burger.js"></script>
</body>
</html>