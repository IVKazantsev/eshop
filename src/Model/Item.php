<?php

namespace N_ONE\App\Model;

use Exception;
use N_ONE\App\Application;
use N_ONE\Core\TemplateEngine\TemplateEngine;
use RuntimeException;

class Item extends Entity
{
	/**
	 * @param Tag[] $tags
	 * @param Image[] $images
	 * @param Attribute[] $attributes
	 */
	public function __construct(
		protected ?int $id,
		private ?string $title,
		private ?int $price,
		private ?string $description,
		private ?int $sortOrder,
		private ?array $tags,
		private ?array $attributes,
		private ?array $images,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		return new static(
			$fields['id'],
			$fields['title'],
			$fields['price'],
			$fields['description'],
			$fields['sortOrder'],
			$fields['tags'],
			$fields['attributes'],
			$fields['images']
		);
	}

	public static function fillAddEditPage(Entity $entity)
	{
		echo 'fill';
		$di = Application::getDI();
		$parentTags = $di->getComponent('tagRepository')->getParentTags();
		$attributes = $di->getComponent('attributeRepository')->getList();

		$itemTags = [];
		$childrenTags = [];
		$specificFields = [
			'description' => TemplateEngine::render('components/editItemDescription', [
				'item' => $entity,
			]),
		];
		foreach ($parentTags as $parentTag)
		{
			$childrenTags[(string)($parentTag->getTitle())] = $di->getComponent('tagRepository')->getByParentId(
				$parentTag->getId()
			);
		}
		foreach ($entity->getTags() as $tag)
		{
			$itemTags[$tag->getParentId()] = $tag->getId();
		}
		$tagsSection = TemplateEngine::render('components/editPageTagsSection', [
			'childrenTags' => $childrenTags,
			'itemTags' => $itemTags,
		]);

		$itemAttributes = $entity->getAttributes();
		if ($itemAttributes)
		{
			$attributesSection = TemplateEngine::render('components/editPageAttributesSection', [
				'attributes' => $attributes,
				'itemAttributes' => $itemAttributes,
			]);
		}
		else
		{
			$attributesSection = TemplateEngine::render('components/editPageAttributesSection', [
				'attributes' => $attributes,
			]);
		}

		$deleteImagesSection = TemplateEngine::render(
			'components/deleteImagesSection', []
		);

		$additionalSections = [
			'tags' => $tagsSection,
			'attributes' => $attributesSection,
			'images' => $deleteImagesSection,
		];

		return ['specificFields' => $specificFields, 'additionalSection' => $additionalSections,];
	}

	public function getAttributes(): ?array
	{
		return $this->attributes;
	}

	public function setAttributes(array $attributes): void
	{
		$this->attributes = $attributes;
	}

	public function getPreviewImage(): Image
	{
		foreach ($this->images as $image)
		{
			if ($image->getType() === 2 && $image->isMain())
			{
				return $image;
			}
		}
		foreach ($this->images as $image)
		{
			if ($image->getType() === 2)
			{
				return $image;
			}
		}

		return $this->images[0];
	}

	public function getFullSizeImages(): array
	{
		$images = [];
		foreach ($this->images as $image)
		{
			if ($image->getType() === 1)
			{
				$images[] = $image;
			}
		}
		if (empty($images))
		{
			return $this->getImages();
		}

		return $images;
	}

	public function getExcludedFields(): array
	{
		return ['tags', 'images', 'attributes'];
	}

	public function getClassname(): string
	{
		$array = explode('\\', __CLASS__);

		return strtolower(end($array));
	}

	public function getField(string $fieldName)
	{
		return $this->$fieldName;
	}

	public function getImages(): array
	{
		return $this->images;
	}

	public function setImages(array $images): void
	{
		$this->images = $images;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	// public function isActive(): bool
	// {
	// 	return $this->isActive;
	// }
	//
	// public function setIsActive(bool $isActive): void
	// {
	// 	$this->isActive = $isActive;
	// }

	public function getPrice(): int
	{
		return $this->price;
	}

	public function setPrice(int $price): void
	{
		$this->price = $price;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	public function getSortOrder(): int
	{
		return $this->sortOrder;
	}

	public function setSortOrder(int $sortOrder): void
	{
		$this->sortOrder = $sortOrder;
	}

	public function getTags(): ?array
	{
		return $this->tags;
	}

	public function setTags(array $tags): void
	{
		$this->tags = $tags;
	}
}