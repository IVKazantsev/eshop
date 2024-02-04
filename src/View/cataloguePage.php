<?php

/**
 * @var array $cars ;
 */

use N_ONE\Core\TemplateEngine\TemplateEngine;

$TE = new TemplateEngine(ROOT . '/src/View/components/');

?>

<div class="catalogue">

	<?php foreach ($cars as $car): ?>
		<?= $TE->render('carCard', ['car' => $car]) ?>
	<?php endforeach; ?>
</div>