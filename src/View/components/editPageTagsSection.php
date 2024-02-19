<?php

/**
 * @var array $childrenTags
 * @var array $itemTags
 */

?>

<div class="form-section">

	<p>Теги:</p>

	<?php foreach ($childrenTags as $parentName => $childTag): ?>
		<div class="tag-group">
			<p><?= $parentName ?>:</p>

			<?php foreach ($childTag as $tag): ?>
				<label for="<?= $tag->getParentId() ?>">
					<input <?php if (array_key_exists($tag->getParentId(), $itemTags))
					{
						echo $itemTags[$tag->getParentId()] === $tag->getId() ? 'checked' : '';
					}
					?> type="radio" name="tags[<?= $tag->getParentId() ?>]" value="<?= $tag->getId() ?>">
					<?= $tag->getTitle() ?>
				</label>

			<?php endforeach; ?>

		</div>

	<?php endforeach; ?>
</div>