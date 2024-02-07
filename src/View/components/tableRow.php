<?php
/**
 * @var Item $item
 */

use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\PriceFormatService;
use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
$price = PriceFormatService::formatPrice($item->getPrice());
?>

<tr class="admin-table-content-row">
	<td class="id-field"><?= $item->getId() ?></td>
	<td class="title-field"><?= $item->getTitle() ?></td>
	<td class="price-field"><?= $price ?></td>
	<td class="description-field"><?= $item->getDescription() ?></td>
	<td class="sort-order-field"><?= $item->getSortOrder() ?></td>
	<td class="actions-field">
		<img src=<?= $iconsPath . 'settings.png' ?> alt="">
		<img src=<?= $iconsPath . 'bin.png' ?> alt="">
	</td>
</tr>