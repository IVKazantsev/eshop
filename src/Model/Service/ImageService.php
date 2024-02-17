<?php

namespace N_ONE\App\Model\Service;

class ImageService
{
	// public static function getPath($id, $itemId, $isMain, $type, $height, $width, $extension): string
	// {
	// 	$size = ($type == 1) ? 'fullsize' : 'preview';
	// 	$description = $isMain ? 'main' : 'base';
	// 	return $itemId . "/$id" . "_$height" . "_$width" . "_$size" . "_$description" . ".$extension";
	// }

	public static function resizeImage($source, $destination, $width, $height): bool
	{
		// Получаем размеры и тип изображения
		[$source_width, $source_height, $source_type] = getimagesize($source);

		// Создаем изображение на основе исходного файла
		switch ($source_type)
		{
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($source);
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($source);
				break;
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($source);
				break;
			default:
				return false; // Неподдерживаемый формат файла
		}

		// Создаем пустое изображение с новыми размерами
		$new_image = imagecreatetruecolor($width, $height);

		// Масштабируем и копируем изображение с измененными размерами
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $source_width, $source_height);

		// Сохраняем измененное изображение
		switch ($source_type)
		{
			case IMAGETYPE_JPEG:
				imagejpeg($new_image, $destination);
				break;
			case IMAGETYPE_PNG:
				imagepng($new_image, $destination);
				break;
			case IMAGETYPE_GIF:
				imagegif($new_image, $destination);
				break;
		}

		// Освобождаем память
		imagedestroy($image);
		imagedestroy($new_image);

		return true;
	}

	public static function changeMainImage()
	{

	}
}