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