<?php

/**
 * @var Entity[] $items
 * @var array $fieldNames
 */

use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;

$fieldNames = array_keys($items[0]);
$iconsPath = Configurator::option('ICONS_PATH');
// var_dump(gettype($items[0]));
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
			<?php foreach ($item as $type => $row): ?>
				<td class="<?= $type ?>-field"><?= $row ?></td>
			<?php endforeach; ?>
			<td class="actions-field">
				<a href="<?= '/admin/items/edit/' . $item['id'] ?>"><img src="<?= $iconsPath
					. 'settings.png' ?>" alt="1"></a>
				<a href="<?= '/admin/items/delete/' . $item['id'] ?>"><img src="<?= $iconsPath . 'bin.png' ?>" alt="1"></a>
			</td>
		</tr>
	<?php endforeach; ?>
</table>