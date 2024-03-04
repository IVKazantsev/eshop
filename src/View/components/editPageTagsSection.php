<?php

/**
 * @var array $childrenTags
 * @var array $itemTags
 */

use N_ONE\App\Model\Service\ValidationService;

?>

<div class="form-section">

	<p>Теги:</p>

	<?php foreach ($childrenTags as $parentName => $childTag): ?>
		<div class="tag-group">
			<p><?= ValidationService::safe($parentName) ?>:</p>

			<?php foreach ($childTag as $tag): ?>
				<label for="<?= $tag->getParentId() ?>">
					<input <?php if (array_key_exists($tag->getParentId(), $itemTags))
					{
						echo $itemTags[$tag->getParentId()] === $tag->getId() ? 'checked' : '';
					}
					?>
						class="specific-input-string"
						type="radio"
						name="tags[<?= $tag->getParentId() ?>]"
						value="<?= $tag->getId() ?>"
					>
					<?=  ValidationService::safe($tag->getTitle()) ?>
				</label>

			<?php endforeach; ?>

		</div>

	<?php endforeach; ?>
</div>