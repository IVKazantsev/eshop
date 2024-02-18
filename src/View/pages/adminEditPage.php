<?php

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;

/**
 * @var Entity $item
 * @var array $statuses
 * @var array $parentTags
 * @var array $additionalSections
 * @var array $specificFields
 */
$fields = array_flip($item->getFieldNames(true));
$scriptsPath = \N_ONE\Core\Configurator\Configurator::option('SCRIPTS_PATH');

?>
<div class="edit-form-container">
	<form action="" class="edit-form" method="post" enctype="multipart/form-data">
		<div class="form-section">
			<p>ID сущности: <?= $item->getId() ?></p>
			<?php foreach ($fields as $field => $value): ?>

				<?php if (in_array($field, ['tags', 'images', 'id', 'dateCreate', 'attributes', 'value'])): {
					continue;
				} endif ?>


				<?php if ($field === 'parentId'): ?>
					<?= $specificFields[$field] ?>
					<?php continue;
				endif; ?>
				<?php if ($field === 'isActive'): ?>
					<?= $specificFields[$field] ?>
					<?php continue;
				endif; ?>
				<?php if ($field === 'description'): ?>
					<?= $specificFields[$field] ?>
					<?php continue;
				endif; ?>
				<?php if ($field === 'status'): ?>
					<?= $specificFields[$field] ?>
					<?php continue;
				endif; ?>
				<?php if ($field === 'statusId'): ?>
				<?= $specificFields[$field] ?>
				<script src='/js/statusChange.js'></script>

			<?php continue;
			endif; ?>
				<label for="<?= $field ?>">
					<?= $field ?>:
					<input id="<?= $field ?>" type="text" name="<?= $field ?>" value="<?= $item->getField($field) ?>">
				</label>

			<?php endforeach; ?>

		</div>
		<?php if (!empty($additionalSections))
		{
			foreach ($additionalSections as $section)
			{
				echo $section;
			}
		}
		else
		{
			echo '';
		}
		?>
		<div class="form-section">

			<button class="submit-button" type="submit">Сохранить</button>

		</div>
	</form>
</div>