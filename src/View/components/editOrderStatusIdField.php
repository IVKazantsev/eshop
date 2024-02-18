<?php

/**
 * @var Order $item
 */

use N_ONE\App\Model\Order;

// var_dump($item);
?>

<label for="statusId">
	statusId:
	<input readonly id="statusId" type="text" name="statusId" value="<?= $item->getField(
		'statusId'
	) ?>">
</label>