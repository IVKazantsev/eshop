<div class="check-order-container order-container">
	<div class="order-title">Проверить заказ</div>
	<form class="check-order-form" action="/orderInfo" method="get">
		<div class="check-order-input-container">
			<label class="check-order-label" for="phone"> Номер телефона</label>
			<input class="check-order-input" type="tel" name="phone" required>
		</div>
		<div class="check-order-input-container">
			<label class="check-order-label" for="number"> Номер заказа</label>
			<input class="check-order-input" type="number" name="number" required>
		</div>
		<button class="order-submit check-order-submit" type="submit">Проверить</button>
	</form>
</div>

<meta name="css" content="/styles/checkOrder.css">
<script src="/js/validatePhone.js"></script>