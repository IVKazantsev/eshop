const inputs = document.getElementsByTagName('input');
for (let i = 0; i < inputs.length; i++)
{
	inputs[i].addEventListener('input', function(event) {
		let value = inputs[i].value.trim();
		if (value !== '')
		{
			inputs[i].setCustomValidity('');
		}
		else
		{
			inputs[i].setCustomValidity('Поле не может быть пустым');
		}
	});
}

const phone = document.querySelector('input[type="tel"]');

phone.addEventListener('input', (event) => {
	const onlyNumbers = event.target.value.replace(/\D/g, '');
	console.log(onlyNumbers);
	if (onlyNumbers.length !== 11) {
		// Показать ошибку с помощью Custom Validity
		phone.setCustomValidity('Неверно введен номер телефона');
	} else {
		// Сбросить ошибку
		phone.setCustomValidity('');
	}
});