<?php
/**
 * @var int $itemId
 */
?>

<form action="/addImages/<?= $itemId?>" method="post" enctype="multipart/form-data" class="image-form">
	<input type="file" name="image[]" accept="image/*" class="choose-file-button" multiple>
	<input type="submit" value="Upload Image" name="submit" class="submit-image-button">
</form>

<!--<label>-->
<!--	<input type="checkbox" name="isMain" class="is-main-image-checkbox">-->
<!--	Is Main Image-->
<!--</label>-->
<!--	<input type="number" name="number" placeholder="Enter a number">-->
<!--	<label>-->
<!--		<input type="checkbox" name="isMain" value="Главная картинка">-->
<!--	</label> Option 1-->
<!--	<label>-->
<!--		<input type="radio" name="radio" value="option2">-->
<!--	</label> Option 2-->
<!--	$number = $_POST['number'];-->
<!--	$radio_option = $_POST['radio'];-->