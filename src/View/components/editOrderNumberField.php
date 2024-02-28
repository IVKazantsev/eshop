<?php

/**
 * @var string $orderNumber
 */

use N_ONE\App\Model\Order;

?>

<label for="statusId">
	orderNumber:
	<input readonly id="orderNumber" type="text" name="orderNumber" value=
	"<?= $orderNumber ?>"
	>
</label>