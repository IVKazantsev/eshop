<?php

/**
 * @var array $car
 */
$iconsPath = \N_ONE\Core\Configurator\Configurator::option('ICONS_PATH');
$imagesPath = \N_ONE\Core\Configurator\Configurator::option('IMAGES_PATH');
$priceString = (string)$car['price'];
$priceString = str_split($priceString, 3);
$priceString = implode(" ", $priceString);
$mileageString = (string)$car['mileage'];
$mileageString = str_split($mileageString, 3);
$mileageString = implode(" ", $mileageString);
?>


<div class="car-card">
	<img class="car-image" src="<?= $imagesPath . '/' . $car['id'] . '/' . $car['mainImage'] ?>" alt="image of a car">
	<div class="description">
		<h2 class="car-name"><?= $car['name'] ?></h2>
		<p class="car-year"><?= $car['year'] ?></p>
		<ul class="car-details">
			<li class="detail-item">
				<div class="car-spec">
					<img src="<?= $iconsPath ?>car-engine.png" alt="">
					<p> <?= $car['horsePower'] . ' л.с., ' . $car['fuelType'] ?></p>
				</div>
			</li>
			<li class="detail-item">
				<div class="car-spec">
					<img src="<?= $iconsPath ?>gearbox.png" alt="">
					<p><?= $car['gearbox'] ?></p>
				</div>
			</li>
			<li class="detail-item">
				<div class="car-spec">
					<img src="<?= $iconsPath ?>rear.png" alt="">
					<p><?= $car['driveType'] ?></p>
				</div>
			</li>
			<li class="detail-item">
				<div class="car-spec">
					<img src="<?= $iconsPath ?>speedometer.png" alt="">
					<p><?= $mileageString . ' км.' ?></p>
				</div>
			</li>

		</ul>
		<p class="price"><?= $priceString ?> ₽</p>
	</div>
</div>