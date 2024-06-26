<?php

/**
 * @var Tag[] $tags
 * @var Attribute[] $attributes
 */

use N_ONE\App\Model\Service\ImageService;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Attribute;
use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
?>

<ul class="item-details">
	<?php foreach ($tags as $tag): ?>
		<li class="detail-item">
			<div class="item-spec">
				<p>
					<img src="<?=  ImageService::getTagIcon($iconsPath . $tag->getParentId())  ?>" alt="">
					<?= ValidationService::safe($tag->getTitle()) ?>
				</p>
			</div>
		</li>
	<?php endforeach; ?>
	<?php if ($attributes): ?>
		<hr>
	<?php endif; ?>
	<?php foreach ($attributes as $attribute): ?>
		<li class="detail-item">
			<div class="item-spec">
				<p>
					<?= ValidationService::safe($attribute->getTitle()) ?> : <?= $attribute->getValue() ?>
				</p>
			</div>
		</li>
	<?php endforeach; ?>
</ul>