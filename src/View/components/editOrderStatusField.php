<?php
/**
 * @var string $field
 * @var array $statuses
 */

use N_ONE\App\Model\Service\ValidationService;

?>


<label for="status">
	status:
	<select id="statusSelect" name="status">
		<?php foreach ($statuses as $statusId => $status): ?>
			<option value="<?= $statusId ?>"><?= ValidationService::safe($status) ?></option>
		<?php endforeach; ?>
	</select>
</label>