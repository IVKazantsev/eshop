<?php
/**
 * @var Image[] $images
 */

use N_ONE\App\Model\Image;
use N_ONE\Core\Configurator\Configurator;

$imagesPath = Configurator::option('IMAGES_PATH');

?>
<?php if($images):?>
	<div class="delete-images-form">
		<p>выбоор фотографий для удаления</p>
		<div class="image-grid">
			<?php foreach ($images as $image): ?>
				<label class="image-item">
					<input type="checkbox" name="imageIds[]" value="<?= $image->getId() ?>">
					<img src="<?= $imagesPath . $image->getPath() ?>" alt="Изображение">
				</label>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif;?>






