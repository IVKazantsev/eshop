<?php

/**
 * @var array $cars ;
 */

use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$TE = new TemplateEngine(Configurator::option("VIEWS_PATH"));

?>

<div class="catalogue">

	<?php foreach ($cars as $car): ?>
		<?= $TE->render('carCard', ['car' => $car]) ?>
	<?php endforeach; ?>
</div>