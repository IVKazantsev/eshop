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
	// return getRequestString;
}

document.getElementById('collect-data-btn').addEventListener('click', function() {
	const getRequestString = collectCheckedData();
	// console.log(window.location.pathname + '?' + getRequestString);
	window.location.href = '/?' + getRequestString;
});