<?php

/**
 * @var Tag[] $tags
 * @var Attribute[] $attributes
 */

use N_ONE\App\Model\Tag;
use N_ONE\App\Model\Attribute;
use N_ONE\Core\Configurator\Configurator;

$iconsPath = Configurator::option('ICONS_PATH');
?>

<ul class="car-details">
	<?php foreach ($tags as $tag): ?>
		<li class="detail-item">
			<div class="car-spec">
				<p>
					<img src="<?= $iconsPath . $tag->getParentId() ?>.svg" alt="">
					<?= $tag->getTitle() ?>
				</p>
			</div>
		</li>
	<?php endforeach; ?>
	<?php foreach ($attributes as $attribute): ?>
		<li class="detail-item">
			<div class="car-spec">
				<p>
					<?= $attribute->getTitle() ?> : <?= $attribute->getValue() ?>
				</p>
			</div>
		</li>
	<?php endforeach; ?>
</ul>