const submitButton = document.querySelector('button[type="submit"][class="order-submit"]');
const form = document.getElementById('order-form');

submitButton.addEventListener('click', (e) => {
	e.preventDefault();
	console.log('click');
	if (form.checkValidity())
	{
		form.submit();
		submitButton.disabled = true;
		setTimeout(() => {
			submitButton.disabled = false;
		}, 5000); // Деактивируем кнопку на 5секунд
	}
	else
	{
		form.reportValidity();
	}
});