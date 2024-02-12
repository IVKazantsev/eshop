<?php

/**
 * @var Item $car
 */

use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\PriceFormatService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');
$priceString = PriceFormatService::formatPrice($car->getPrice())

?>

<div class="car-info">

	<div class="car-image-gallery" id="slider">
		<?php foreach ($car->getFullSizeImages() as $image): ?>
			<div class="slide"><img src="<?= $imagesPath . $image->getPath() ?>" alt="Изображение"></div>
		<?php endforeach; ?>
		<script src="/js/slider.js"></script>
		<button id="prev">&#10094</button>
		<button id="next">&#10095</button>
	</div>


	<div class="car-specs">
		<h1 class="car-title-details"><?= $car->getTitle() ?></h1>
		<h3 class="year-title-details"> 2024</h3>
		<hr>
		<?= TemplateEngine::render('components/tags', [
			'tags' => $car->getTags(),
		]) ?>
		<p class="detail-price"><?= $priceString ?> </p>
		<a class="buy-link" href="<?= '/products/' . $car->getId() . '/order' ?>">КУПИТЬ</a>
	</div>
	<div class="car-description">
		<h2>ОПИСАНИЕ  МАШИНЫ</h2>
		<hr>
		<p class="car-description-text"> <?= $car->getDescription() ?>
		</p>
	</div>
</div>