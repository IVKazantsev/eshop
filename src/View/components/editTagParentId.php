<?php
/**
 * @var Tag $tag
 * @var array $parentTags
 */

use N_ONE\App\Model\Tag;

?>


<?php if (!$tag->getParentId() && !$tag->getId()): ?>
	<label class="checkbox-label">
		Является категорией:
		<input type="checkbox" id="isParent" value="1">
	</label>
	<label for="parentId" id="parentId">
		parentId:
		<select id="statusSelect" name="parentId">
			<?php foreach ($parentTags as $parentTag): ?>
				<option value="<?= $parentTag->getId() ?>"><?= $parentTag->getTitle() ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<div class="add-images-section" hidden>
		<p>Для загрзки логотипа категории</p>
		<input type="file" name="image[]" accept="image/*" class="choose-file-button" >
	</div>
<?php elseif (!$tag->getParentId()): ?>
	<div class="add-images-section">
		<p>Для смены логотипа категории</p>
		<input type="file" name="image[]" accept="image/*" class="choose-file-button" >
	</div>
<?php else: ?>
	<label for="parentId">
		parentId:
		<select id="statusSelect" name="parentId">
			<?php foreach ($parentTags as $parentTag): ?>
				<option value="<?= $parentTag->getId() ?>"><?= $parentTag->getTitle() ?></option>
			<?php endforeach; ?>
		</select>
	</label>
<?php endif; ?>

<script>
	const checkbox = document.getElementById('isParent');
	const parentIdLabel = document.getElementById('parentId');
	const parentIdSelect = document.querySelector('select[name="parentId"]');
	const addImagesSection = document.querySelector('.add-images-section');

	checkbox.addEventListener('change', () => {
		if (!checkbox.checked)
		{
			parentIdLabel.removeAttribute('hidden');
			addImagesSection.setAttribute('hidden', '');
			if (parentIdSelect)
			{
				parentIdSelect.setAttribute('name', 'parentId');
			}
		}
		else
		{
			parentIdLabel.setAttribute('hidden', '');
			addImagesSection.removeAttribute('hidden');
			if (parentIdSelect)
			{
				parentIdSelect.removeAttribute('name');
			}
		}
	});

</script>
