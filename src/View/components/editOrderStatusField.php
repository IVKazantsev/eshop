<?php
/**
 * // * @var string $field
 * @var array $statuses
 */

?>


<label for="status">
	status:
	<select id="statusSelect" name="status">
		<?php foreach ($statuses as $statusId => $status): ?>
			<option value="<?= $statusId ?>"><?= $status ?></option>
		<?php endforeach; ?>
	</select>
</label>