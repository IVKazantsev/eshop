<?php

/**
 * @var string $orderNumber
 */

?>

<div class="process-order-container">
	<form id="process-order-form" action="/successOrder" method="post">
		<input type="hidden" name="orderNumber" value="<?= $orderNumber ?>">
		<div class="process-order-text">Если страница не обновляется в течение 10 секунд, нажмите на кнопку «Подтвердить»</div>
		<button class="process-order-submit-button" type="submit">Подтвердить</button> <!-- Сделано для людей без поддержки JS скриптов -->
	</form>
</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">
<script>
	document.getElementById('process-order-form').submit();
</script>