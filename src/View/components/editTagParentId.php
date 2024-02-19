<?php
/**
 * @var Entity $item
 * @var array $parentTags
 */

use N_ONE\App\Model\Entity;

?>


<?php if (!$item->getParentId() && !$item->getId()): ?>
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
<?php elseif (!$item->getParentId()): ?>
	<label hidden for="parentId">
		<input type="hidden" name="parentId" value="">
	</label>
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

	checkbox.addEventListener('change', () => {
		if (!checkbox.checked)
		{
			parentIdLabel.removeAttribute('hidden');
			if (parentIdSelect)
			{
				parentIdSelect.setAttribute('name', 'parentId');
			}
		}
		else
		{
			parentIdLabel.setAttribute('hidden', '');
			if (parentIdSelect)
			{
				parentIdSelect.removeAttribute('name');
			}
		}
	});

</script>