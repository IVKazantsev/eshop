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


<div class="item-card">
	<a class="item-card-link" href='<?= '/products/' . $item->getId() ?>'>
		<?php if($item->getImages()):?>
			<img class="item-image" src="<?= $imagesPath . $item->getPreviewImage()->getPath() ?>" alt="image of an item">
		<?php else:?>
			<img class="item-image" src="<?= $imagesPath . 'plugs/imageNotFound.jpeg' ?>" alt="image of an item">
		<?php endif;?>
		<div class="description">
			<h2 class="item-name"><?= ValidationService::safe($item->getTitle()) ?></h2>
			<?= TemplateEngine::render('components/tags', [
				'tags' => $item->getTags(),
				'attributes' => $item->getAttributes(),
			]) ?>
			<p class="price"><?= $priceString ?> </p>
		</div>
	</a>
</div>