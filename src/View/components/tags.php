<?php

/**
 * @var Tag[] $tags
 */

use N_ONE\App\Model\Tag;
$iconsPath = \N_ONE\Core\Configurator\Configurator::option('ICONS_PATH');
?>

<ul class="car-details">
	<?php foreach ($tags as $tag): ?>
		<li class="detail-item">
			<div class="car-spec">
				<p>
					<?php if ($tag->getValue() === null): ?>
						<img src="<?= $iconsPath . $tag->getParentId() ?>.svg" alt="">
						<?= $tag->getTitle() ?>
					<?php else: ?>
						<img src="<?= $iconsPath . $tag->getId() ?>.svg" alt="">
						<?= $tag->getValue() ?>
					<?php endif; ?>
				</p>
			</div>
		</li>
	<?php endforeach; ?>
</ul>