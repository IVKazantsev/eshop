function toggleDropdown(event)
{

	if (event.target.tagName === 'INPUT' || event.target.tagName === 'LABEL' || event.target.tagName === 'DIV')
	{
		return;
	}

	event.preventDefault();
	const dropdownContent = document.getElementById('dropdown-content-' + event.currentTarget.querySelector('.dropdown-toggle').dataset.parentId);
	const dropdownIcon = event.currentTarget.querySelector('.dropdown-icon');

	dropdownContent.style.display = dropdownContent.style.display === 'flex' ? 'none' : 'flex';

	if (dropdownContent.style.display === 'flex')
	{
		dropdownIcon.classList.remove('chevron-up');
		dropdownIcon.classList.add('chevron-down');
	}
	else
	{
		dropdownIcon.classList.remove('chevron-down');
		dropdownIcon.classList.add('chevron-up');
	}
}

function collectTagData()
{
	const checkboxes = document.querySelectorAll('.tag-checkbox:checked');
	const selectedTags = {};
	checkboxes.forEach(function(checkbox) {
		const parentId = checkbox.dataset.parentId;
		const childId = checkbox.value;

		if (!selectedTags[parentId])
		{
			selectedTags[parentId] = [];
		}
		selectedTags[parentId].push(childId);
	});
	let tagsData = [];

	for (const parentId in selectedTags)
	{
		if (selectedTags.hasOwnProperty(parentId))
		{
			tagsData.push(parentId + ':[' + selectedTags[parentId].join(',') + ']');
		}
	}
	return tagsData.length ? 'selectedTags=[' + tagsData.join(';') + ']' : '';
}

function collectRangeFieldData()
{
	const rangeFields = document.querySelectorAll('.range_input');
	let rangeData = [];

	for (let i = 0; i < rangeFields.length; i += 2)
	{
		let minValue = parseFloat(rangeFields[i].value);
		let maxValue = parseFloat(rangeFields[i + 1].value);
		if (isNaN(minValue) || isNaN(maxValue))
		{
			continue;
		}
		if (minValue >= maxValue)
		{
			[minValue, maxValue] = [maxValue, minValue];
		}
		const attributeId = rangeFields[i].id.split('_')[1];
		const rangeParam = attributeId + '=[' + minValue + '-' + maxValue + ']';
		rangeData.push(rangeParam);
	}

	return rangeData.length ? 'attributes=[' + rangeData.join(';') + ']' : '';
}

function collectCheckedData()
{
	const tagData = collectTagData();
	const rangeData = collectRangeFieldData();
	return [tagData, rangeData].filter(Boolean).join('&');
}

document.getElementById('collect-data-btn').addEventListener('click', function() {
	const getRequestString = collectCheckedData();
	// console.log(window.location.pathname + '?' + getRequestString);
	window.location.href = '/?' + getRequestString;
});

function applyStateFromUrl()
{
	const urlParams = new URLSearchParams(window.location.search);
	const selectedTagsParam = urlParams.get('selectedTags');
	const attributesParam = urlParams.get('attributes');
	if (selectedTagsParam)
	{
		const selectedTags = selectedTagsParam.replaceAll('[', '').replaceAll(']', '');
		let selectedTagsGroups = selectedTags.split(';');
		let tagsResult = [];
		selectedTagsGroups.map((value, index) => {
			let [parentId, childId] = selectedTagsGroups[index].split(':');
			childId = childId.split(',');
			tagsResult.push({ parentId: parentId, childId: childId });
		});
		if (tagsResult)
		{
			tagsResult.forEach((element) => {
				const dropdown = document.getElementById(`dropdown-content-${element.parentId}`);
				const dropdownIcon = document.getElementById(`chevron-${element.parentId}`);
				dropdown.style.display = 'flex';
				dropdownIcon.classList.remove('chevron-up');
				dropdownIcon.classList.add('chevron-down');

				element.childId.forEach((childId) => {
					const checkbox = document.querySelector(`input[type="checkbox"][value="${childId}"]`);
					checkbox.checked = true;
				});
			});
		}
	}

	if (attributesParam)
	{
		const attributes = attributesParam.replaceAll('[', '').replaceAll(']', '');
		let selectedAttributesGroups = attributes.split(';');
		let attributesResult = [];
		selectedAttributesGroups.map((value, index) => {
			let [parentId, values] = selectedAttributesGroups[index].split('=');
			let [fromValue, toValue] = values.split('-');
			attributesResult.push({ parentId: parentId, fromValue: fromValue, toValue: toValue });
		});
		if (attributesResult)
		{
			attributesResult.forEach((element) => {
				const fromInput = document.getElementById(`input1_${element.parentId}`);
				const toInput = document.getElementById(`input2_${element.parentId}`);
				const dropdown = document.getElementById(`dropdown-content-${element.parentId}`);
				const dropdownIcon = document.getElementById(`chevron-${element.parentId}`);
				if (fromInput)
				{
					dropdown.style.display = 'flex';
					fromInput.value = element.fromValue;
					toInput.value = element.toValue;
					dropdownIcon.classList.remove('chevron-up');
					dropdownIcon.classList.add('chevron-down');
				}
			});
		}
	}
}

document.addEventListener('DOMContentLoaded', applyStateFromUrl);