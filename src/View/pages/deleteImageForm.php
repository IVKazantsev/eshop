<?php
/**
 * @var Image[] $images
 */

use N_ONE\App\Model\Image;
use N_ONE\Core\Configurator\Configurator;

$imagesPath = Configurator::option('IMAGES_PATH');

// $jsonData = json_encode(array_map(function($image) {
// 	return [
// 		'path' => $image->getPath(),
// 	];
// }, $images));
//
// die();
?>

<form action="/deleteImages" method="post">
	<div class="image-grid">
<!--		<input type="hidden" name="jsonData" value="--><?php //= htmlspecialchars($jsonData) ?><!--">-->
		<?php foreach ($images as $image): ?>
			<label class="image-item">
				<input type="checkbox" name="imageIds[]" value="<?= $image->getId() ?>">
				<img src="<?= $imagesPath . $image->getPath() ?>" alt="Изображение">
			</label>
		<?php endforeach; ?>
	</div>
	<button class="submit-delete-button" type="submit">Submit</button>
</form>




