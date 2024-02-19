<?php
/**
 * @var Entity $item
 */

use N_ONE\App\Model\Entity;

?>

<label for="description">
	description:
	<textarea id="description" name="description"><?= $item->getField('description') ?></textarea>
</label>