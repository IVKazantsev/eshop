<?php
/**
 * @var Entity $item
 * @var array $fieldNames
 */

use N_ONE\App\Model\Entity;

// var_dump($fieldNames);
?>


<tr class="admin-table-header-row">
	<?php foreach ($fieldNames as $fieldName): ?>
		<th><?= $fieldName ?></th>
	<?php endforeach; ?>
	<th>Действия</th>
</tr>