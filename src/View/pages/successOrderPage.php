<?php

/**
 * @var string $orderNumber
 */
use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
?>

<div class="success-order-container">
	<div class="success-img-container">
		<img  class="success-img" src="<?= $iconsPath . 'checkmark.svg' ?>" alt="image of checkmark">
	</div>
	<div class="success-order-title">
		Ваш заказ успешно оформлен!
	</div>
	<div class="order-number-container">
		<div class="order-number-header">НОМЕР ВАШЕГО ЗАКАЗА:</div>
		<div class="specific-order-number">#<?= $orderNumber ?></div>
	</div>
	<div class="order-info">
		Мы свяжемся с Вами в ближайшее время
	</div>
</div>