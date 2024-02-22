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
	<div class="sidebar">
		<div class="tags-container">
			<div class="tags-title">КАТЕГОРИИ</div>
			<ul class="tags">
				<?php if (isset($tags[""])): ?>
					<?php foreach ($tags[""] as $parentTag): ?>
						<li class="tag-item dropdown">
							<a class="dropdown-toggle" href="#" data-parent-id="<?= $parentTag->getId(
							) ?>" onclick="toggleDropdown(event)">
								<?= $parentTag->getTitle() ?>
							</a>

							<div class="dropdown-content" id="dropdown-content-<?= $parentTag->getId() ?>">

								<?php foreach ($tags[$parentTag->getId()] as $childTag): ?>

									<input type="checkbox" class="tag-checkbox" data-parent-id="<?= $parentTag->getId(
									) ?>" value="<?= $childTag->getId() ?>" id="input-<?= $childTag->getId() ?>">

									<label for="input-<?= $childTag->getId() ?>"><?= $childTag->getTitle() ?></label>
								<?php endforeach; ?>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php foreach ($attributes as $attribute): ?>
					<li class="tag-item">
						<a class="dropdown-toggle" href="#" data-parent-id="<?= $attribute->getId(
						) ?>" onclick="toggleDropdown(event)">
							<?= $attribute->getTitle() ?>
						</a>
						<div class="dropdown-content" id="dropdown-content-<?= $attribute->getId() ?>">
							<input class="range_input" id="input1_<?= $attribute->getId(
							) ?>" type="number" min="0" max="999" name="" data-attribute-title="<?= $attribute->getTitle(
							) ?>"> -
							<input class="range_input" id="input2_<?= $attribute->getId(
							) ?>" type="number" min="0" max="999" data-attribute-title="<?= $attribute->getTitle() ?>">
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
		<div class="bar">
			<div class="searchbar">
				<form class="search-form" action="/" method="get">
					<div class="search-icon-and-input">
						<input name="SearchRequest" type="text" placeholder="Поиск" value="<?= ValidationService::safe($currentSearchRequest ?? '')?>" class="search-input" required>
					</div>
					<button type="submit" class="search-button btn"><img class="search-icon" src="<?= $iconsPath ?>search.svg" alt="search-icon"/></button>
				</form>
				<a class="check-order-link" href="/checkOrder">Проверить заказ</a>
			</div>
		</div>
	</header>

	<main>
		<?= $content ?>
	</main>

	<footer>
		Created by N_ONE team 2024
	</footer>
</div>

<?php
//TODO ВЫНЕСТИ ЭТО ВСЕ НАХОЙ ОТСЕДА
$tagsData = [];

$parentTags = isset($tags['']) ? $tags[''] : [];
$childTags = array_filter($tags, function($key) {
	return is_numeric($key);
},                        ARRAY_FILTER_USE_KEY);

foreach ($parentTags as $parentTag)
{
	$tagsData[$parentTag->getId()] = [
		'id' => $parentTag->getId(),
		'title' => $parentTag->getTitle(),
	];
}

foreach ($childTags as $parentId => $childTagGroup)
{
	$tagsData[$parentId] = [];
	foreach ($childTagGroup as $childTag)
	{
		$tagsData[$parentId][] = [
			'id' => $childTag->getId(),
			'title' => $childTag->getTitle(),
		];
	}
}

$tagsJson = json_encode($tagsData);

?>
<script>
	const tagsData = <?php echo $tagsJson; ?>;


</script>
<script src="/js/categoryFilter.js">
</script>
</body>


</html>