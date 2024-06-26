<?php
/**
 * @var string $entity
 * @var int $entityId
 * @var string $action
 */

use N_ONE\App\Model\Service\ValidationService;

?>

<div class="confirm-delete-container">
	<form class="confirm-delete-form" action="" method="post">
		Вы уверены, что хотите <?= $action ?> <?= ValidationService::safe($entity) ?> № <?= $entityId ?>?
		<div class="buttons-container">
			<button class="confirm-delete-button" type="submit"><?= $action ?></button>
			<a class="cancel-delete-link" href="/admin/items">Отмена</a>
		</div>
	</form>
</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">