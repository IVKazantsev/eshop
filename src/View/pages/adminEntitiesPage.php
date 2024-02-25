<?php

/**
 * @var array $entities
 * @var string $previousPageUri
 * @var string $nextPageUri
 * @var int $isActive
 */

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\Core\TemplateEngine\TemplateEngine;
$classname = $entities[0]->getClassname();
?>

<?php if ($isActive === 0):?>
	<a href="<?= "/admin/{$classname}s/?isActive=1" ?>" class="show-active">Показать Активные</a>
<?php else:?>
	<a href="<?= "/admin/{$classname}s/?isActive=0" ?>" class="show-delete">Показать Удаленные</a>
<?php endif;?>

<div class="admin-content">
	<?= TemplateEngine::renderTable($entities, $isActive) ?>
</div>
<div class="pagination">

	<?php if (isset($previousPageUri)):?>
		<a href="<?=$previousPageUri?>">&#10094</a>
	<?php endif?>

	<?php if (isset($nextPageUri)):?>
		<a href="<?=$nextPageUri?>">&#10095</a>
	<?php endif?>

</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">