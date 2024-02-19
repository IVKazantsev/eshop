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