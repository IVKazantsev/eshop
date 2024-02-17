<?php

/**
 * @var array $attributes
 * @var array $itemAttributes
 */

?>

	<div class="form-section attribute-container">
		<?php foreach ($attributes as $attribute): ?>
			<label class="attribute" for="<?= $attribute->getTitle() ?>">
				<?= $attribute->getTitle() ?>
				<input class="attribute-input" type="text" name="attributes[<?= $attribute->getId() ?>]"
					   value="<?= getAttributeValue(
						   $attribute,
						   $itemAttributes
					   ) ?>">
			</label>
		<?php endforeach; ?>
	</div>

<?php
function getAttributeValue($attribute, $itemAttributes)
{
	foreach ($itemAttributes as $itemAttribute)
	{
		if ($attribute->getId() === $itemAttribute->getId())
		{
			return $itemAttribute->getValue();
		}
	}

	return $attribute->getValue();
}

?>