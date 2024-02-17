<?php
/**
 * @var int $itemId
 */
?>
<div class="add-images-form">
	<p>Выбор фотографий для добавления</p>
<!--	<form action="/addImages/--><?php //= $itemId?><!--" method="post" enctype="multipart/form-data" class="image-form">-->
		<input type="file" name="image[]" accept="image/*" class="choose-file-button" multiple>
<!--		<input type="submit" value="Upload Image" name="submit" class="submit-image-button">-->
<!--	</form>-->
</div>
