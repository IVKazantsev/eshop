const numericInputs = document.querySelectorAll('input.number, input#price, input#sortOrder');

numericInputs.forEach(input => {
	input.addEventListener('input', (event) => {
		const inputValue = event.target.value.trim();

		// Проверка наличия числа в диапазоне от 0 до 1 миллиарда
		if (inputValue === '' || (/^(0|[1-9]\d{0,8})$/.test(inputValue) && parseInt(inputValue) <= 1000000000)) {
			input.setCustomValidity('');
		} else {
			input.setCustomValidity('допустимы только числа');
		}
	});
});
