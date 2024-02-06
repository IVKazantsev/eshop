<?php

/**
 * @var Item $car
 */

use N_ONE\App\Model\Item;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$iconsPath = Configurator::option('ICONS_PATH');
$imagesPath = Configurator::option('IMAGES_PATH');
$priceString = $car->getPrice();
$priceString = number_format($priceString, 0, '', ' ');
?>

<div class="order-container">
	<div class="order-title">Оформление заказа</div>
	<form class="order-form" method="post">
		<div class="user-info-container">
			<div class="order-form-title">
				Контактная информация
			</div>
			<ul class="user-info">
				<li class="user-info-item">
					<label class="user-info-item_label" for="name">ФИО</label>
					<input class="user-info-item_input" type="text" name="name" required>
				</li>
				<li class="user-info-item">
					<label class="user-info-item_label" for="email">Адрес электронной почты</label>
					<input class="user-info-item_input" type="email" name="email" required>
				</li>
				<li class="user-info-item">
					<label class="user-info-item_label" for="phone">Номер телефона</label>
					<input class="user-info-item_input" type="tel" name="phone" required>
				</li>
				<li class="user-info-item">
					<label class="user-info-item_label" for="address">Адрес доставки</label>
					<input class="user-info-item_input" type="text" name="address" required>
				</li>
			</ul>
		</div>
		<div class="order-info-container">
			<div class="order-info-title">Ваш заказ</div>
			<table class="order-table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th class="order-td order-th gray-cell">
							Товар
						</th>
						<th class="order-td order-th gray-cell">
							Стоимость
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="order-td order-car-info">
							<div class="order-img-container">
								<img class="order-image" src="<?= $imagesPath . $car->getPreviewImage()->getPath() ?>" alt="image of a car">
							</div>
							<div class="order-car-title">
								<?= $car->getTitle() ?>
							</div>
						</td>
						<td class="order-td">
							<?= $priceString ?> ₽
						</td>
					</tr>
					<tr>
						<td class="order-td gray-cell">
							Итого
						</td>
						<td class="order-td">
							<?= $priceString ?> ₽
						</td>
					</tr>
					<tr>
						<td class="order-td">
						</td>
						<td class="order-td">
							<input class="order-submit" type="submit" value="Заказать">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>