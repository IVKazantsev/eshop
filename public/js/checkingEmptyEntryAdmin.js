document.addEventListener('DOMContentLoaded', function() {
	const numberInputs = document.querySelectorAll('.specific-input-int');
	const textInputs = document.querySelectorAll('.specific-input-string');

	numberInputs.forEach(input => {
		input.addEventListener('input', function(event) {
			const value = input.value.trim();
			if (value === '') {
				input.setCustomValidity('Поле не может быть пустым');
			} else if (!/^\d+$/.test(value)) {
				input.setCustomValidity('Поле должно содержать только числа');
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
			} else if (!/^\d+$/.test(value)) {
				isValid = false;
				input.setCustomValidity('Поле должно содержать только числа');
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

