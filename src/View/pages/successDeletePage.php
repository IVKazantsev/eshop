<?php

use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
?>

<div class="success-delete-container">
	<div class="success-delete-img-container">
		<img  class="success-img" src="<?= $iconsPath . 'checkmark.svg' ?>" alt="image of checkmark">
	</div>
	<div class="success-delete-title">
		Вы успешно удалили сущность
	</div>
</div>