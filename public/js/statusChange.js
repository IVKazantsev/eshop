document.addEventListener('DOMContentLoaded', function() {
	const statusSelect = document.getElementById('statusSelect');
	const statusIdInput = document.getElementById('statusId');
	console.log('bruh');
	statusSelect.addEventListener('change', function() {
		// Update the statusId input field with the selected option's value
		statusIdInput.value = this.value;
	});
});