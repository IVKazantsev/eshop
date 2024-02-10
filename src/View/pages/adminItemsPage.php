<?php

/**
 * @var array $items
 */

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;
use N_ONE\Core\TemplateEngine\TemplateEngine;

?>
<div class="admin-content">
	<?= TemplateEngine::renderTable($items) ?>
</div>