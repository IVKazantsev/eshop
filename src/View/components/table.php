<?php

/**
 * @var Entity[] $entities
 * @var int $isActive
 */

use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;

$fieldNames = $entities[0]->getFieldNames();
$iconsPath = Configurator::option('ICONS_PATH');
$classname = $entities[0]->getClassname();
?>
<a href="<?= "/admin/{$classname}/add" ?>" class="add-entity-button">Добавить</a>
<table class="admin-table">

	<tr class="admin-table-header-row">
		<?php foreach ($fieldNames as $fieldName): ?>
			<th><?= $fieldName ?></th>
		<?php endforeach; ?>
		<th id="actions-column">Действия</th>
	</tr>
	<?php foreach ($entities as $entity): ?>
		<tr class="admin-table-content-row">

			<?php foreach ($fieldNames as $fieldName): ?>
				<td class="<?= $fieldName ?>-field"><?= $entity->getField($fieldName) ?></td>
			<?php endforeach; ?>

			<td class="actions-field">
				<a
					href="<?= "/admin/{$classname}s/edit/" . $entity->getId() ?>">
					<img src="<?= $iconsPath . 'settings.svg' ?>" alt="1">
				</a>
				<?php if ($isActive): ?>
					<a
						href="<?= "/admin/{$classname}s/delete/" . $entity->getId() ?>">
						<img src="<?= $iconsPath . 'bin.svg' ?>" alt="1">
					</a>
				<?php else: ?>
					<a
						href="<?= "/admin/{$classname}s/restore/" . $entity->getId() ?>">
						<img src="<?= $iconsPath . 'reactivate.svg' ?>" alt="1">
					</a>
				<?php endif; ?>

			</td>
		</tr>
	<?php endforeach; ?>
</table>