function toggleDropdown(event)
{
	event.preventDefault();
	const dropdownContent = document.getElementById('dropdown-content-' + event.target.dataset.parentId);
	dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
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
	let outputString = 'selectedTags=[';
	let isFirst = true;
	for (const parentId in selectedTags)
	{
		if (selectedTags.hasOwnProperty(parentId))
		{
			if (!isFirst)
			{
				outputString += ';';
			}
			outputString += parentId + ':[' + selectedTags[parentId].join(',') + ']';
			isFirst = false;
		}
	}

	outputString += ']';

	console.log(outputString);
	return outputString;
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
		const attributeTitle = rangeFields[i].dataset.attributeTitle;

		const rangeParam = attributeId + '=[' + minValue + '-' + maxValue + ']';
		rangeData.push(rangeParam);
	}

	return rangeData.length ? 'attributes=[' + rangeData.join(';') + ']' : '';
}

function collectCheckedData()
{
	const tagData = collectTagData();
	const rangeData = collectRangeFieldData();
	const getRequestString = [tagData, rangeData].filter(Boolean).join('&');
	return getRequestString;
}

document.getElementById('collect-data-btn').addEventListener('click', function() {
	const getRequestString = collectCheckedData();
	// console.log(window.location.pathname + '?' + getRequestString);
	window.location.href = window.location.pathname + '?' + getRequestString;
});