<?php

/**
 * @var array $cars;
 * @var TemplateEngine $TE
 */

use N_ONE\Core\TemplateEngine\TemplateEngine;

?>

<div class="catalogue">

	<?php foreach ($cars as $car): ?>
		<?= $TE->render('components/carCard', [
			'car' => $car,
			'TE' => $TE
		])
		?>
	<?php endforeach; ?>
</div>