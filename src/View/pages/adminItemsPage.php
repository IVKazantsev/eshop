<?php

/**
 * @var array $items
 * @var string $previousPageUri
 * @var string $nextPageUri
 */

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\Core\TemplateEngine\TemplateEngine;

?>
<div class="admin-content">
	<?= TemplateEngine::renderTable($items) ?>
</div>
<div class="pagination">

	<?php if (isset($previousPageUri)):?>
		<a href="<?=$previousPageUri?>">&#10094</a>
	<?php endif?>

	<?php if (isset($nextPageUri)):?>
		<a href="<?=$nextPageUri?>">&#10095</a>
	<?php endif?>

</div>