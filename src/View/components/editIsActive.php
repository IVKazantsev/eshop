<?php
/**
 * @var Entity $entity
 */

use N_ONE\App\Model\Entity;

?>

<label class="checkbox-label" for="isActive">
	isActive:
	<input type="hidden"
		   name="isActive"
		   value="0"
	/>
	<input id="isActive"
		   type="checkbox"
		   name="isActive"
		   value="1"
		<?= $entity->getField("isActive") ? 'checked' : '' ?>
	/>
</label>