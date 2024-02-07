<?php

/**
 * @var Item[] $items
 */

use N_ONE\App\Model\Item;
use N_ONE\Core\TemplateEngine\TemplateEngine;

// var_dump($items);
?>
<div class="admin-content">
	<table class="admin-table">
		<tr class="admin-table-header-row">
			<th>ID</th>
			<th>Название</th>
			<th>Стоимость</th>
			<th>Описание</th>
			<th>Приоритет</th>
			<th>Действия</th>
		</tr>
		<?php foreach ($items as $item): ?>
			<?= TemplateEngine::render('components/tableRow', [
				'item' => $item,
			]) ?>
		<?php endforeach; ?>

	</table>
</div>