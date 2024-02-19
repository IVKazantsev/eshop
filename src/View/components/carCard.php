<?php

/**
 * @var Item $car
 * @var TemplateEngine $TE
 */

use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\PriceFormatService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');
$priceString = PriceFormatService::formatPrice($car->getPrice())
?>


<div class="car-card">
	<a class="car-card-link" href='<?= '/products/' . $car->getId() ?>'>
		<?php if($car->getImages()):?>
			<img class="car-image" src="<?= $imagesPath . $car->getPreviewImage()->getPath() ?>" alt="image of a car">
		<?php else:?>
			<img class="car-image" src="<?= $imagesPath . 'plugs/imageNotFound.jpeg' ?>" alt="image of a car">
		<?php endif;?>
		<div class="description">
			<h2 class="car-name"><?= $car->getTitle() ?></h2>
			<p class="car-year">2024</p>
			<?= TemplateEngine::render('components/tags', [
				'tags' => $car->getTags(),
				'attributes' => $car->getAttributes(),
			]) ?>
			<p class="price"><?= $priceString ?> </p>
		</div>
	</a>
</div>