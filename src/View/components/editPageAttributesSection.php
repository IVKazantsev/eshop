<?php

/**
 * @var array $attributes
 * @var array $itemAttributes
 */

?>

	<div class="form-section attribute-container">
		<p>Аттрибуты:</p>
		<?php foreach ($attributes as $attribute): ?>
			<label class="attribute" for="<?= $attribute->getTitle() ?>">
				<?= $attribute->getTitle() ?>
				<input class="attribute-input" type="text" name="attributes[<?= $attribute->getId() ?>]"
					<?php if (empty($itemAttributes))
					{
						echo "value={$attribute->getValue()}";
					}
					else
					{
						echo 'value=' . getAttributeValue(
								$attribute,
								$itemAttributes
							);
					} ?>>

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