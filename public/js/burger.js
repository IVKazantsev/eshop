document.querySelector('.burger-button').addEventListener('click', function() {
	this.classList.toggle('active');
	document.querySelector('.sidebar').classList.toggle('open');
});