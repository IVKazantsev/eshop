<?php
/**
 * // * @var Item $item
 * @var Entity $item
 * @var array $fieldNames
 */

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\App\Model\Service\PriceFormatService;
use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
// $price = PriceFormatService::formatPrice($item->getPrice());
// $itemId = $item->getId();
// $itemAsArray = (array)$item;
// var_dump($item);
// exit();
//TODO сделать адаптивные поля под entity
?>

<tr class="admin-table-content-row">
	<?php //foreach ($fieldNames as $fieldName): ?>
	<!--	<th>--><?php //= $itemAsArray[(string)$fieldName] ?><!--</th>-->
	<?php //endforeach; ?>
	<?php foreach ($item as $type => $row): ?>
		<td class="<?= $type ?>-field"><?= $row ?></td>
	<?php endforeach; ?>
	<td class="actions-field">
		<a href="<?= '/admin/items/edit/' . $item['id'] ?>"><img src="<?= $iconsPath . 'settings.png' ?>" alt="1"></a>
		<a href="<?= '/admin/items/delete/' . $item['id'] ?>"><img src="<?= $iconsPath . 'bin.png' ?>" alt="1"></a>
		<!--		<a href="--><?php //= '/admin/items/edit/' . $itemId ?><!--"><img src="--><?php //= $iconsPath . 'settings.png' ?><!--" alt="1"></a>-->
		<!--		<a href="--><?php //= '/admin/items/delete/' . $itemId ?><!--"><img src="--><?php //= $iconsPath . 'bin.png' ?><!--" alt="1"></a>-->
	</td>
</tr>