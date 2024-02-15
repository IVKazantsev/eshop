<?php

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;

/**
 * @var Entity $item
 * @var array $statuses
 * @var array $parentTags
 * @var array $childrenTags
 * @var array $itemTags
 */
$fields = array_flip($item->getFieldNames(true));
$scriptsPath = \N_ONE\Core\Configurator\Configurator::option('SCRIPTS_PATH');

?>
<div class="edit-form-container">
	<form action="" class="edit-form" method="post">
		<div class="form-section">
			<p>ID сущности: <?= $item->getId() ?></p>
			<?php foreach ($fields as $field => $value): ?>

				<?php if (
					in_array($field, ['tags', 'images', 'id', 'dateCreate'])
				): {
					continue;
				} endif ?>

				<?php if ($field === 'status'): ?>
					<label for="<?= $field ?>">
						<?= $field ?>:
						<select id="statusSelect" name="<?= $field ?>">
							<?php foreach ($statuses as $statusId => $status): ?>

								<option value="<?= $statusId ?>"><?= $status ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<?php continue; endif; ?>
				<?php if ($field === 'parentId'): ?>
					<label for="<?= $field ?>">
						<?= $field ?>:
						<select id="statusSelect" name="<?= $field ?>">
							<?php foreach ($parentTags as $parentTag): ?>
								<option value="<?= $parentTag->getId() ?>"><?= $parentTag->getTitle() ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<?php continue; endif; ?>
				<?php if ($field === 'isActive'): ?>
					<label class="checkbox-label" for="<?= $field ?>">
						<?= $field ?>:
						<input id="<?= $field ?>"
							   type="checkbox"
							   name="<?= $field ?>"
							   value="1"
							<?= $item->getField($field) ? 'checked' : '' ?>
						>
						<input type="hidden"
							   name="<?= $field ?>"
							   value="0"
						>
					</label>
					<?php continue; endif; ?>

				<?php if ($field === 'statusId'): ?>
					<label for="<?= $field ?>">
						<?= $field ?>:
						<input readonly id="<?= $field ?>" type="text" name="<?= $field ?>" value="<?= $item->getField(
							$field
						) ?>">
					</label>
					<?php continue; endif; ?>
				<label for="<?= $field ?>">
					<?= $field ?>:
					<input id="<?= $field ?>" type="text" name="<?= $field ?>" value="<?= $item->getField($field) ?>">
				</label>

			<?php endforeach; ?>

		</div>
		<div class="form-section">

			<?php if ($item instanceof Item): ?>
				<p>Теги:</p>

				<?php foreach ($childrenTags as $parentName => $childTag): ?>
					<div class="tag-group">
						<p><?= $parentName ?>:</p>

						<?php foreach ($childTag as $tag): ?>
							<label for="<?= $tag->getParentId() ?>">
								<input <?= $itemTags[$tag->getParentId()] === $tag->getId() ? 'checked'
									: '' ?> type="radio" name="<?= $tag->getParentId() ?>" value="<?= $tag->getId() ?>">
								<?= $tag->getTitle() ?>
							</label>

						<?php endforeach; ?>

					</div>

				<?php endforeach; ?>

			<?php endif; ?>
			<button class="submit-button" type="submit">Сохранить</button>

		</div>
	</form>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const statusSelect = document.getElementById('statusSelect');
		const statusIdInput = document.getElementById('statusId');
		statusSelect.addEventListener('change', function() {
			statusIdInput.value = this.value;
		});
	});
</script>