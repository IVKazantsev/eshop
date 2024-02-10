<?php

/**
 * @var Entity[] $items
 */

use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;

$fieldNames = $items[0]->getFieldNames();
$iconsPath = Configurator::option('ICONS_PATH');
$classname = $items[0]->getClassname();
?>
<table class="admin-table">

	<tr class="admin-table-header-row">
		<?php foreach ($fieldNames as $fieldName): ?>
			<th><?= $fieldName ?></th>
		<?php endforeach; ?>
		<th>Действия</th>
	</tr>
	<?php foreach ($items as $item): ?>
		<tr class="admin-table-content-row">

			<?php foreach ($fieldNames as $fieldName): ?>
				<td class="field"><?= $item->getField($fieldName) ?></td>
			<?php endforeach; ?>

			<td class="actions-field">
				<a href="<?= "/admin/{$classname}s/edit/" . $item->getId() ?>"><img src="<?= $iconsPath
					. 'settings.png' ?>" alt="1"></a>
				<a href="<?= "/admin/{$classname}s/delete/" . $item->getId() ?>"><img src="<?= $iconsPath
					. 'bin.png' ?>" alt="1"></a>
			</td>
		</tr>
	<?php endforeach; ?>
</table>