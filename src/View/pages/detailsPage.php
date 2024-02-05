<?php

/**
 * @var Item $car
 * @var TemplateEngine $TE
 */

use N_ONE\App\Model\Item;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');
$priceString = $car->getPrice();
$priceString = number_format($priceString, 0, '', ' ');
?>

<div class="car-info">
	<div class="car-image-gallery">
		<div class="car-main-image-container">
			<img class="car-main-image" src="<?= $imagesPath . $car->getPreviewImage()->getPath(
			) ?>" alt="image of a car">
		</div>
	</div>
	<div class="car-specs">
		<h1 class="car-title-details"><?= $car->getTitle() ?></h1>
		<h3 class="year-title-details"> 2024</h3>
		<?= $TE->render('components/tags', [
			'tags' => $car->getTags(),
			])
		?>
		<p class="price"><?= $priceString ?> ₽</p>
		<button class="buy-button">КУПИТЬ</button>
	</div>
	<div class="car-description">
		<h2>Описание машины</h2>
		<p class="car-description-text"> <?= $car->getDescription() ?>
		</p>
	</div>
</div>