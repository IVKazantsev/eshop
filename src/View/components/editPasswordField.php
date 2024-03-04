<?php

/**
 * @var \N_ONE\App\Model\Entity $user
 */

?>

<?php if ($user->getId() !== null): ?>
	<label class="checkbox-label">
		Изменить пароль?
		<input type="checkbox" id="change-password" value="0">
	</label>
	<label hidden for="pass" id="password-label">
		password:
		<input
			hidden
			disabled
			class="specific-input-password"
			id="password-field"
			type="password" name="pass"
			value=""
		>
	</label>
<?php else: ?>
	<label for="pass" id="password-label">
		password:
		<input
			class="specific-input-password"
			id="password-field"
			type="password" name="pass"
			value=""
		>
	</label>
<?php endif; ?>

<script>
	const checkbox = document.getElementById('change-password');
	const passwordLabel = document.getElementById('password-label');
	const passwordField = document.getElementById('password-field');
	checkbox.addEventListener('change', () => {
		if (checkbox.checked)
		{
			passwordLabel.removeAttribute('hidden');
			passwordField.removeAttribute('disabled');
		}
		else
		{
			passwordLabel.setAttribute('hidden', 'true');
			passwordField.setAttribute('disabled', 'true');
		}
	});
</script>