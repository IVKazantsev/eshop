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


<div class="car-card">


	<img class="car-image" src="<?= $imagesPath . $car->getPreviewImage()->getPath() ?>" alt="image of a car">
	<div class="description">
		<h2 class="car-name"><?= $car->getTitle() ?></h2>
		<p class="car-year">2024</p>
		<?= $TE->render('components/tags', [
			'tags' => $car->getTags(),
			])
		?>
		<p class="price"><?= $priceString ?> ₽</p>
		<a href = '<?= '/products/' . $car->getId() ?>'> Подробнее </a>
	</div>

</div>