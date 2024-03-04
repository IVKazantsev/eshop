// document.addEventListener('DOMContentLoaded', function() {
// 	const numberInputs = document.querySelectorAll('.specific-input-int');
// 	const textInputs = document.querySelectorAll('.specific-input-string, textarea');
// 	const passInput = document.getElementById('password-field');
//
// 	numberInputs.forEach(input => {
// 		input.addEventListener('input', function(event) {
// 			const value = input.value.trim();
// 			if (value === '')
// 			{
// 				input.setCustomValidity('Поле не может быть пустым');
// 			}
// 			else if (!/^\d+$/.test(value) || parseInt(value) > 1000000000)
// 			{
// 				input.setCustomValidity('не коректное число');
// 			}
// 			else
// 			{
// 				input.setCustomValidity('');
// 			}
// 		});
// 	});
//
// 	textInputs.forEach(input => {
// 		input.addEventListener('input', function(event) {
// 			const value = input.value.trim();
// 			if (value === '')
// 			{
// 				input.setCustomValidity('Поле не может быть пустым');
// 			}
// 			else
// 			{
// 				input.setCustomValidity('');
// 			}
// 		});
// 	});
// 	passInput.addEventListener('input', function(event) {
// 		const value = event.target.value;
// 		if (value.length < 8)
// 		{
// 			event.target.setCustomValidity('Пароль не может быть короче 8 символов');
// 		}
// 		else
// 		{
// 			event.target.setCustomValidity('');
// 		}
// 	});
//
// 	// Добавляем обработчик для события submit на форму
// 	const form = document.querySelector('form');
// 	form.addEventListener('submit', function(event) {
// 		let isValid = true;
//
// 		numberInputs.forEach(input => {
// 			const value = input.value.trim();
// 			if (value === '')
// 			{
// 				isValid = false;
// 				input.setCustomValidity('Поле не может быть пустым');
// 			}
// 			else if (!/^\d+$/.test(value) || parseInt(value) > 1000000000)
// 			{
// 				isValid = false;
// 				input.setCustomValidity('не коректное число');
// 			}
// 		});
//
// 		textInputs.forEach(input => {
// 			const value = input.value.trim();
// 			if (value === '')
// 			{
// 				isValid = false;
// 				input.setCustomValidity('Поле не может быть пустым');
// 			}
// 		});
// 		const passValue = passInput.value.trim();
// 		if (passValue === '' && !passInput.hidden)
// 		{
// 			isValid = false;
// 			passInput.setCustomValidity('Поле не может быть пустым');
// 		}
// 		if (!isValid)
// 		{
// 			event.preventDefault(); // Предотвращаем отправку формы, если есть некорректные поля ввода
// 		}
// 	});
// });

document.addEventListener('DOMContentLoaded', function() {
	const numberInputs = document.querySelectorAll('.specific-input-int');
	const textInputs = document.querySelectorAll('.specific-input-string, textarea');

	numberInputs.forEach(input => {
		input.addEventListener('input', function(event) {
			const value = input.value.trim();
			if (value === '') {
				input.setCustomValidity('Поле не может быть пустым');
			} else if (!/^\d+$/.test(value) || parseInt(value) > 1000000000) {
				input.setCustomValidity('не коректное число');
			} else {
				input.setCustomValidity('');
			}
		});
	});

	textInputs.forEach(input => {
		input.addEventListener('input', function(event) {
			const value = input.value.trim();
			if (value === '') {
				input.setCustomValidity('Поле не может быть пустым');
			} else {
				input.setCustomValidity('');
			}
		});
	});

	// Добавляем обработчик для события submit на форму
	const form = document.querySelector('form');
	form.addEventListener('submit', function(event) {
		let isValid = true;

		numberInputs.forEach(input => {
			const value = input.value.trim();
			if (value === '') {
				isValid = false;
				input.setCustomValidity('Поле не может быть пустым');
			} else if (!/^\d+$/.test(value) || parseInt(value) > 1000000000) {
				isValid = false;
				input.setCustomValidity('не коректное число');
			}
		});

		textInputs.forEach(input => {
			const value = input.value.trim();
			if (value === '') {
				isValid = false;
				input.setCustomValidity('Поле не может быть пустым');
			}
		});

		if (!isValid) {
			event.preventDefault(); // Предотвращаем отправку формы, если есть некорректные поля ввода
		}
	});
});

