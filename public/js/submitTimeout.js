let submitButton = document.querySelector('button[type="submit"]');
submitButton.addEventListener('click', e => {
	e.preventDefault(); // Предотвращаем отправку формы
	submitButton.disabled = true;
	setTimeout(() => submitButton.disabled = false, 500);
});