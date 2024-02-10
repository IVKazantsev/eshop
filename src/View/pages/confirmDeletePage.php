<?php
/**
 * @var Item $item
 */

use N_ONE\App\Model\Item;
use N_ONE\Core\Configurator\Configurator;

$imagesPath = Configurator::option('IMAGES_PATH');

?>

<div class="confirm-delete-container">
	<form class="confirm-delete-form" action="" method="post">
		Вы уверены, что хотите удалить товар #<?= $item->getId() ?>:
		<img class="car-image" src="<?= $imagesPath . $item->getPreviewImage()->getPath() ?>" alt="image of a car">
		<?= $item->getTitle() ?>?
		<div class="buttons-container">
			<button class="confirm-delete-button" type="submit">Удалить</button>
			<a class="cancel-delete-link" href="/admin/items">Отмена</a>
		</div>
	</form>
</div>