<?php

use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
?>

<div class="success-update-container">
	<div class="success-update-img-container">
		<img  class="success-img" src="<?= $iconsPath . 'checkmark.svg' ?>" alt="image of checkmark">
	</div>
	<div class="success-update-title">
		Вы успешно изменили сущность
	</div>
</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">