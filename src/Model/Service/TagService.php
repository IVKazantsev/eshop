<?php

namespace N_ONE\App\Model\Service;

use N_ONE\App\Model\Tag;

class TagService
{
	/**
	 * @param Tag[] $tags
	 */
	public static function reformatTags(array $tags): array
	{

		$groupedTags = [];

		foreach ($tags as $tag)
		{
			// Проверяем, есть ли уже массив с тегами для данного parentID
			if (!isset($groupedTags[$tag->getParentId()]))
			{
				$groupedTags[$tag->getParentId()] = [];
			}

			// Добавляем текущий тег в соответствующий массив
			$groupedTags[$tag->getParentId()][] = $tag;
		}

		return $groupedTags;
	}

}