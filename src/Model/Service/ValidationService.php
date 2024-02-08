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
}