<?php

/**
 * @var array $cars
 * @var string $previousPageUri
 * @var string $nextPageUri
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

<div class="pagination">

	<?php if ($previousPageUri):?>
		<a href="<?=$previousPageUri?>">&#10094</a>
	<?php endif?>

	<?php if ($nextPageUri):?>
		<a href="<?=$nextPageUri?>">&#10095</a>
	<?php endif?>

</div>