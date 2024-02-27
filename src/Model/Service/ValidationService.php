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
	public static function validateEntryField(array|string|null $field): array|string
	{
		if (is_array($field))
		{
			$result = [];
			foreach ($field as $key => $value)
			{
				$validatedField = trim($value);
				if ($validatedField !== "")
				{
					$result[$key] = $validatedField;
				}
			}
			return $result;
		}
		$validatedField = trim($field);
		if ($validatedField === "")
		{
			throw new ValidateException("No field should be empty");
		}

		return $validatedField;
	}

	public static function safe(?string $value): string
	{
		return htmlspecialchars($value, ENT_QUOTES);
	}

	/**
	 * @throws ValidateException
	 * @throws Exception
	 */
	public static function validateImage($image, int $i = 0): bool
	{
		$allowed_formats = ["jpg", "png", "jpeg", "svg"];
		$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];;// Разрешенные форматы файлов
		$imageFileType = strtolower(pathinfo(basename($image["image"]["name"][$i]), PATHINFO_EXTENSION));
		$fileMimeType = mime_content_type($image["image"]["tmp_name"][$i]);
		$fileInfo = @getimagesize($image["image"]["tmp_name"][$i]);

		// Проверка наличия файла
		if (!isset($image["image"]))
		{
			throw new FileException("image {$image["image"]["tmp_name"][$i]}");
		}

		if (!in_array($fileMimeType, $allowedMimeTypes))
		{
			throw new FileException("image {$image["image"]["tmp_name"][$i]}");
		}

		// Проверка размера файла
		if ($image["image"]["size"][$i] > 500000)
		{
			throw new ValidateException("image {$image["image"]["tmp_name"][$i]}");
		}


		if (!in_array($imageFileType, $allowed_formats))
		{
			throw new ValidateException("image {$image["image"]["tmp_name"][$i]}");
		}


		if ($fileInfo === false && $imageFileType !== "svg")
		{
			// Файл не является изображением
			throw new ValidateException("image {$image["image"]["tmp_name"][$i]}");
		}

		return true;
	}

	public static function validateMetaTag($html, $tagName): ?string
	{
		$pattern = '/<meta\s+name="' . preg_quote($tagName, '/') . '"\s+content="([^"]*)"\s*\/?>/i';
		if (preg_match($pattern, $html, $matches))
		{
			return $matches[1];
		}

		return null;
	}
}