<?php

/**
 * @var array $cars
 */

use N_ONE\Core\TemplateEngine\TemplateEngine;

?>

<div class="catalog">
	<?php foreach ($cars as $car): ?>
		<?= TemplateEngine::render('components/carCard', [
			'car' => $car,
		])
		?>
	<?php endforeach; ?>
</div>