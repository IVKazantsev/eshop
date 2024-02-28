<?php

/**
 * @var Item $item
 */

use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\PriceFormatService;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');
$priceString = PriceFormatService::formatPrice($item->getPrice())

?>

<div class="item-info">

	<div class="item-image-gallery" id="slider">
		<?php if($item->getImages()):?>
			<?php foreach ($item->getFullSizeImages() as $image): ?>
			<div class="slide"><img src="<?= $imagesPath . $image->getPath() ?>" alt="image of an item"></div>
		<?php endforeach; ?>
			<script src="/js/slider.js"></script>
			<button id="prev">&#10094</button>
			<button id="next">&#10095</button>
		<?php else:?>
			<div class="plug-image"><img src="<?= $imagesPath . 'plugs/imageNotFound.jpeg' ?>" alt="image of an item"></div>
		<?php endif;?>

	</div>


	<div class="item-specs">
		<h1 class="item-title-details"><?= ValidationService::safe($item->getTitle()) ?></h1>
		<hr>
		<?= TemplateEngine::render('components/tags', [
			'tags' => $item->getTags(),
			'attributes' => $item->getAttributes(),
		]) ?>
		<p class="detail-price"><?= $priceString ?> </p>
		<a class="buy-link" href="<?= '/products/' . $item->getId() . '/order' ?>">КУПИТЬ</a>
	</div>
	<div class="item-description">
		<h2>ОПИСАНИЕ  МАШИНЫ</h2>
		<hr>
		<p class="item-description-text"> <?= ValidationService::safe($item->getDescription()) ?>
		</p>
	</div>
</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">