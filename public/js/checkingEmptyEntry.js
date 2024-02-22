const form = document.querySelector('form');

form.addEventListener('submit', function(event) {
	const inputs = form.getElementsByTagName('input');
	let isEmpty = false;

	for (let i = 0; i < inputs.length; i++) {
		let value = inputs[i].value.trim();
		if (value === '') {
			isEmpty = true;
			inputs[i].setCustomValidity('Поле не может быть пустым');
		} else {
			inputs[i].setCustomValidity('');
		}
	}

	if (isEmpty) {
		event.preventDefault(); // Предотвращаем отправку формы, если есть пустые поля ввода
	}
});

// Добавляем обработчик для события input на каждое поле ввода, чтобы сбросить пользовательское сообщение об ошибке при вводе в поле
const inputs = form.getElementsByTagName('input');
for (let i = 0; i < inputs.length; i++) {
	inputs[i].addEventListener('input', function(event) {
		if (this.value.trim() !== '') {
			this.setCustomValidity('');
		}
	});
}
