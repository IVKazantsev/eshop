<?php

/**
 * @var Tag[] $tags
 */

use N_ONE\App\Model\Tag;

?>

<ul class="car-details">
	<?php foreach (
		$tags

		as $tag
	): ?>
		<li class="detail-item">
			<div class="car-spec">
				<!--				<img src="--><?php //= $iconsPath ?><!--car-engine.png" alt="">-->
				<p> <?= $tag->getTitle() ?></p>
			</div>
		</li>
	<?php endforeach; ?>
</ul>