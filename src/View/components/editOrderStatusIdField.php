<?php

/**
 * @var Order $order
 */

use N_ONE\App\Model\Order;

?>

<label for="statusId">
	statusId:
	<input class = "specific-input-int" readonly id="statusId" type="text" name="statusId" value=
	"<?= $order->getField('statusId') ?>"
	>
</label>