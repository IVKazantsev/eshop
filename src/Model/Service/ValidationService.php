<?php

namespace N_ONE\App\Model\Service;

class ValidationService
{
	public static function validatePhoneNumber(string $phone): ?string
	{
		$phone = preg_replace('/\D/', '', $phone);
		if (strlen($phone) === 10)
		{
			return '8' . $phone;
		}

		if (strlen($phone) !== 11)
		{
			return null;
		}
		if($phone[0] === '7')
		{
			$phone[0] = '8';
		}
		return $phone;
	}
	public static function safe(string $value): string
	{
		return htmlspecialchars($value, ENT_QUOTES);
	}

	public static function validateImage($image): bool
	{
		$allowed_formats = ["jpg", "png", "jpeg",];
		$allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg'];// Разрешенные форматы файлов
		$imageFileType = strtolower(pathinfo(basename($image["image"]["name"]),PATHINFO_EXTENSION));

		// Проверка наличия файла
		if(isset($image["image"]))
		{
			if(!getimagesize($image["image"]["tmp_name"]))
			{
				return false;
			}
		}

		// Проверка размера файла
		if ($image["image"]["size"] > 500000)
		{
			return false;
		}

		if(!in_array($imageFileType, $allowed_formats))
		{
			return false;
		}

		$file_info = @getimagesize($image["image"]["tmp_name"]);
		if ($file_info === false)
		{
			// Файл не является изображением
			return false;
		}

		// Проверяем MIME-тип изображения (допустимые MIME-типы можно дополнительно проверять)
		if (!in_array($file_info['mime'], $allowed_mime_types))
		{
			// Недопустимый MIME-тип изображения
			return false;
		}

		return true;
	}
}