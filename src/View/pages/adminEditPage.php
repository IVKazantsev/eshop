<?php

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;

/**
 * @var Entity $item
 * @var array $statuses
 * @var array $parentTags
 * @var string $additionalSection
 */
$fields = array_flip($item->getFieldNames(true));
$scriptsPath = \N_ONE\Core\Configurator\Configurator::option('SCRIPTS_PATH');
// var_dump($item->getParentId());
?>
<div class="edit-form-container">
	<form action="" class="edit-form" method="post">
		<div class="form-section">
			<p>ID сущности: <?= $item->getId() ?></p>
			<?php foreach (
				$fields

				as $field => $value
			): ?>

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
					<?php if (!$item->getParentId()): ?>
						<label hidden for="<?= $field ?>">
							<input type="hidden" name="<?= $field ?>" value="">
						</label>
					<?php else: ?>
						<label for="<?= $field ?>">
							<?= $field ?>:
							<select id="statusSelect" name="<?= $field ?>">
								<?php foreach ($parentTags as $parentTag): ?>
									<option value="<?= $parentTag->getId() ?>"><?= $parentTag->getTitle() ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					<?php endif; ?>

					<?php continue;
				endif; ?>
				<?php if ($field === 'isActive'): ?>
					<label class="checkbox-label" for="<?= $field ?>">
						<?= $field ?>:
						<input type="hidden"
							   name="<?= $field ?>"
							   value="0"
						/>
						<input id="<?= $field ?>"
							   type="checkbox"
							   name="<?= $field ?>"
							   value="1"
							<?= $item->getField($field) ? 'checked' : '' ?>
						/>
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
		<?php echo $additionalSection ?? '' ?>
		<div class="form-section">

			<button class="submit-button" type="submit">Сохранить</button>

		</div>
	</form>
</div>
<script src='/js/statusChange.js'></script>