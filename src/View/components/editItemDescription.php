<?php
/**
 * @var Entity $item
 */

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Service\ValidationService;

?>

<label for="description">
	description:
	<textarea id="description" name="description"><?= ValidationService::safe($item->getField('description')) ?></textarea>
</label>