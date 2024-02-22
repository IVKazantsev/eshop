<?php

namespace N_ONE\App\Model\Service;

use Exception;
use N_ONE\Core\Exceptions\FileException;
use N_ONE\Core\Exceptions\ValidateException;

class ValidationService
{
	/**
	 * @throws ValidateException
	 */
	public static function validatePhoneNumber(string $phone): string
	{
		$phone = preg_replace('/\D/', '', $phone);

		if (strlen($phone) !== 11)
		{
			throw new ValidateException("Phone entered incorrectly");
		}
		if ($phone[0] === '7')
		{
			$phone[0] = '8';
		}

		return $phone;
	}

	/**
	 * @throws ValidateException
	 */
	public static function validateEmailAddress(string $email): string
	{
		$email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
		if (!$email)
		{
			throw new ValidateException("Email entered incorrectly");
		}

		return $email;
	}

	/**
	 * @throws ValidateException
	 */
	public static function validateEntryField(string $field): string
	{
		$validatedField = trim($field);
		if($validatedField === "")
		{
			throw new ValidateException("No field should be empty");
		}
		return $validatedField;
	}

	public static function safe(string $value): string
	{
		return htmlspecialchars($value, ENT_QUOTES);
	}


	/**
	 * @throws ValidateException
	 * @throws Exception
	 */
	public static function validateImage($image, int $i = 0): bool
	{
		$allowed_formats = ["jpg", "png", "jpeg",];
		$allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg'];// Разрешенные форматы файлов
		$imageFileType = strtolower(pathinfo(basename($image["image"]["name"][$i]),PATHINFO_EXTENSION));

		// Проверка наличия файла
		if (isset($image["image"]))
		{
			if (!getimagesize($image["image"]["tmp_name"][$i]))
			{
				throw new FileException("image ");
			}
		}

		// Проверка размера файла
		if ($image["image"]["size"][$i] > 500000)
		{
			throw new ValidateException("image $image");
		}

		if (!in_array($imageFileType, $allowed_formats))
		{
			throw new ValidateException("image $image");
		}

		$file_info = @getimagesize($image["image"]["tmp_name"][$i]);
		if ($file_info === false)
		{
			// Файл не является изображением
			throw new ValidateException("image $image");
		}

		// Проверяем MIME-тип изображения (допустимые MIME-типы можно дополнительно проверять)
		if (!in_array($file_info['mime'], $allowed_mime_types, true))
		{
			// Недопустимый MIME-тип изображения
			throw new ValidateException("image $image");
		}

		return true;
	}

	public static function validateMetaTag($html, $tagName)
	{
		$pattern = '/<meta\s+name="' . preg_quote($tagName, '/') . '"\s+content="([^"]*)"\s*\/?>/i';
		if (preg_match($pattern, $html, $matches)) {

			return $matches[1];
		}
		else
		{

			return null;
		}
	}
}