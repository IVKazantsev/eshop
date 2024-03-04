<?php

/**
 * @var array $items
 * @var array $attributes
 * @var string $previousPageUri
 * @var string $nextPageUri
 *
 */

use N_ONE\Core\TemplateEngine\TemplateEngine;

?>
<label>
	<select name="select" id="sort">
		<option value="" disabled selected>Выберите вариант сортировки</option>
		<?php foreach ($attributes as $attribute): ?>
			<option value="<?= $attribute->getId() ?>-ASC">
				по возрастанию <?= $attribute->getTitle() ?>
			</option>
			<option value="<?= $attribute->getId() ?>-DESC">
				по убыванию <?= $attribute->getTitle() ?>
			</option>
		<?php endforeach; ?>
		<option value="PRICE-ASC">по возрастанию цены</option>
		<option value="PRICE-DESC">по убыванию цены</option>
	</select>
</label>
<div class="catalog">

	<?php foreach ($items as $item): ?>
		<?= TemplateEngine::render('components/itemCard', [
			'item' => $item,
		]) ?>
	<?php endforeach; ?>

	<div class="pagination">

		<?php if ($previousPageUri): ?>
			<a href="<?= $previousPageUri ?>">&#10094</a>
		<?php endif ?>

		<?php if ($nextPageUri): ?>
			<a href="<?= $nextPageUri ?>">&#10095</a>
		<?php endif ?>
	</div>
</div>

<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">