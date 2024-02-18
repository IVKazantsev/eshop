<?php
/**
 * @var Image[] $images
 */

use N_ONE\App\Model\Image;
use N_ONE\Core\Configurator\Configurator;

$imagesPath = Configurator::option('IMAGES_PATH');

?>
<div class="delete-images-section">
	<p>Изображения:</p>
	<p>Выберите фотографии для добавления</p>
	<input type="file" name="image[]" accept="image/*" class="choose-file-button" multiple>
	<?php if (!empty($images)): ?>
		<p>Выберите фотографии для удаления</p>
		<div class="image-grid">
			<?php foreach ($images as $image): ?>
				<label class="image-item">
					<input type="checkbox" name="imageIds[]" value="<?= $image->getId() ?>">
					<img src="<?= $imagesPath . $image->getPath() ?>" alt="Изображение">
				</label>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>