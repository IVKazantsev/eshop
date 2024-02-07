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

		return $phone;
	}
}