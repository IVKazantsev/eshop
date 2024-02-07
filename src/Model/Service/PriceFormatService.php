<?php

namespace N_ONE\App\Model\Service;

class PriceFormatService
{

	public static function formatPrice(string $price): ?string
	{
		return number_format($price, 0, '', ' ') . ' ₽';

	}

}