<?php
/**
 * @var Item $item
 * @var Order $order
 */

use N_ONE\App\Model\Item;
use N_ONE\App\Model\Order;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\Core\Configurator\Configurator;

$imagesPath = Configurator::option('IMAGES_PATH');
$priceString = $item->getPrice();

?>

<table class="order-table order-info-table" cellpadding="0" cellspacing="0">
	<thead>
	<tr class="order-info-tr">
		<th class="order-td order-info-td order-th gray-cell order-id">
			Заказ
		</th>
		<th class="order-td order-info-td order-th gray-cell order-item-info">
			Товар
		</th>
		<th class="order-td order-info-td order-th gray-cell">
			Стоимость
		</th>
		<th class="order-td order-info-td order-th gray-cell">
			Статус
		</th>
	</tr>
	</thead>
	<tbody>
	<tr class="order-info-tr">
		<td class="order-td order-info-td order-id">
			<?= $order->getId() ?>
		</td>
		<td class="order-td order-info-td order-item-info">
			<div class="order-img-container">

				<?php if ($item->getImages()): ?>
					<img
						class="order-image"
						src="<?= $imagesPath . $item->getPreviewImage()->getPath() ?>"
						alt="image of an item">
				<?php else: ?>
					<img
						class="order-image"
						src="<?= $imagesPath . 'plugs/imageNotFound.jpeg' ?>"
						alt="image of an item">
				<?php endif; ?>
			</div>
			<div class="order-item-title">
				<?= ValidationService::safe($item->getTitle()) ?>
			</div>
		</td>
		<td class="order-td order-info-td order-info-price">
			<?= $priceString ?> ₽
		</td>
		<td class="order-td order-info-td order-status">
			<?= ValidationService::safe($order->getStatus()) ?>
		</td>
	</tr>
	</tbody>
</table>
<meta name="css" content="<?= '/styles/orderInfo.css' ?>">