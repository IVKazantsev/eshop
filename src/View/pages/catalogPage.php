<?php

/**
 * @var array $items
 * @var string $previousPageUri
 * @var string $nextPageUri
 */

use N_ONE\Core\TemplateEngine\TemplateEngine;
?>

<div class="catalog">
	<?php foreach ($items as $item): ?>
		<?= TemplateEngine::render('components/itemCard', [
			'item' => $item,
		])
		?>
	<?php endforeach; ?>

	<div class="pagination">

		<?php if ($previousPageUri):?>
			<a href="<?=$previousPageUri?>">&#10094</a>
		<?php endif?>

		<?php if ($nextPageUri):?>
			<a href="<?=$nextPageUri?>">&#10095</a>
		<?php endif?>
	</div>
</div>

<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">