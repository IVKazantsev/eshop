<div class="check-order-container order-container">
	<div class="order-title">Проверить заказ</div>
	<form class="check-order-form" action="/orderInfo" method="get">
		<label class="check-order-label" for="number"> Номер заказа</label>
		<div class="check-order-input-container">
			<input class="check-order-input" type="number" name="number" required>
			<button class="order-submit check-order-submit" type="submit">Проверить</button>
		</div>
	</form>
</div>