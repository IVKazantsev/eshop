<?php

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\Core\Configurator\Configurator;

/**
 * @var Entity $entity
 * @var array $statuses
 * @var array $additionalSections
 * @var array $specificFields
 */
$fields = array_flip($entity->getFieldNames());
foreach ($fields as $field => $value)
{
	if (
		array_key_exists(
			$field,
			array_merge($specificFields ?? [], $additionalSections ?? [], ['id' => ''])
		)
	)
	{
		unset($fields[$field]);
	}
}

?>
<div class="edit-form-container">
	<form action="" class="edit-form" method="post" enctype="multipart/form-data">
		<div class="form-section">
			<p>ID сущности: <?= $entity->getId() ?></p>

			<?php foreach ($fields as $field => $value): ?>
				<label for="<?= $field ?>">
					<?= $field ?>:
					<input
						class="specific-input-<?= $entity->getPropertyType($field) ?>"
						id="<?= $field ?>"
						type="text" name="<?= $field ?>"
						value="<?= ValidationService::safe($entity->getField($field)) ?>"
					>
				</label>
			<?php endforeach; ?>

			<?php foreach ($specificFields as $field): ?>
				<?= $field ?>
			<?php endforeach; ?>

		</div>
		<?php if (!empty($additionalSections)): ?>
			<?php foreach ($additionalSections as $section): ?>
				<?= $section ?>
			<?php endforeach; ?>
		<?php endif; ?>


		<div class="form-section">
			<button class="submit-button" type="submit">Сохранить</button>
		</div>
	</form>
</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">
<script src="/js/checkingEmptyEntryAdmin.js"></script>
<script src="/js/validateNumber.js"></script>