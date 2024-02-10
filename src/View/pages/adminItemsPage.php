<?php

/**
 * // * @var Item[] $items
 * @var Entity[] $items
 */

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\Core\TemplateEngine\TemplateEngine;

// $fieldNames = $items[0]->getFieldNames($items[0]->getExludedFields());
// $headerFieldNames = $items[0]->getFieldNamesForHeader($items[0]->getExludedFields());
// $obj = new \ReflectionObject($items[0]);
//
// // var_dump();
// $itemForTable = $items[0]->prepareEntityForTable($items[0]->getExludedFields());
// exit();
// var_dump($items);
?>
<div class="admin-content">
	<table class="admin-table">
		<?= TemplateEngine::render(
			'components/tableHeader', [
										// 'fieldNames' => $headerFieldNames,
										'fieldNames' => array_keys($items[0]),
									]
		) ?>
		<?php foreach ($items as $item): ?>
			<?= TemplateEngine::render('components/tableRow', [
				'item' => $item,
				// 'fieldNames' => $fieldNames,
			]) ?>
		<?php endforeach; ?>

	</table>
</div>