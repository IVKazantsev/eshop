<?php

namespace N_ONE\App\Service;

class ValidationService
{
	public static function validatePhoneNumber(string $phone): ?string
	{
		$phone = preg_replace('/\D/', '', $phone);
		if (count($phone) === 10)
		{
			return '8' . $phone;
		}

		if (count($phone) !== 11)
		{
			return null;
		}

		return $phone;
	}
}