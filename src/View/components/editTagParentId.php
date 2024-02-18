<?php
/**
 * @var Entity $item
 * @var array $parentTags
 */

use N_ONE\App\Model\Entity;

?>

<?php if (!$item->getParentId()): ?>
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