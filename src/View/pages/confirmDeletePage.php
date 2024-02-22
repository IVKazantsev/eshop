<?php
/**
 * @var string $entity
 * @var int $entityId
 */

?>

<div class="confirm-delete-container">
	<form class="confirm-delete-form" action="" method="post">
		Вы уверены, что хотите удалить <?= $entity ?> #<?= $entityId ?>?
		<div class="buttons-container">
			<button class="confirm-delete-button" type="submit">Удалить</button>
			<a class="cancel-delete-link" href="/admin/items">Отмена</a>
		</div>
	</form>
</div>
<meta name="css" content="<?= '/styles/' . basename(__FILE__, '.php') . '.css' ?>">