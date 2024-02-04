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

<!--<main class="details">-->
<div class="car-info">
	<div class="car-image-gallery">
		<div class="car-main-image-container">
			<img class="car-main-image" src="<?= $imagesPath
			. '/'
			. $car['id']
			. '/'
			. $car['mainImage'] ?>" alt="image of a car">
		</div>
	</div>
	<div class="car-specs">
		<h1 class="car-title-details"><?= $car['name'] ?></h1>
		<h3 class="year-title-details"><?= $car['year'] ?></h3>
		<ul class="car-details">
			<li class="detail-item">
				<div class="car-spec">
					<img src="<?= $iconsPath . 'car-engine.png' ?>" alt="">
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
		<button class="buy-button">КУПИТЬ</button>
	</div>
	<div class="car-description">
		<h2>Описание машины</h2>
		<p class="car-description-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda at atque beatae deleniti dolorem dolorum eum expedita facere, fuga hic in, ipsum labore laborum molestias nam numquam possimus qui quibusdam quisquam quo sint tempore temporibus tenetur vel velit voluptate voluptatem. Beatae earum fugit hic praesentium. Animi beatae dolore esse hic in odit provident quod voluptate. Aperiam assumenda ducimus, eius impedit iusto magni perspiciatis quibusdam quod sit! Accusamus ad assumenda consequatur ea ex expedita, impedit neque, pariatur perferendis porro quam, quod saepe temporibus. Commodi delectus dolor dolore error eveniet praesentium reprehenderit rerum tempore voluptas. Ad commodi corporis cupiditate doloribus est eveniet explicabo, fuga incidunt iste laborum libero minima molestias nemo nisi nostrum perspiciatis placeat quaerat repellat tempora totam? Adipisci dolor ipsum similique sint. A accusamus, culpa deleniti eligendi impedit laborum magnam molestiae molestias mollitia natus necessitatibus neque numquam quae quidem reiciendis sint vel veniam voluptate? Ad aspernatur assumenda consequatur corporis cum est ex fugiat, harum nisi quis quisquam sit veritatis, voluptatibus? Adipisci asperiores, dolores enim facilis illum perferendis placeat ratione repellendus saepe sit suscipit tenetur vel? Autem est expedita itaque quasi! Ab accusamus accusantium asperiores aspernatur, aut beatae delectus eveniet itaque laborum maxime nam nobis perferendis qui, reprehenderit sequi. Architecto, quas?
		</p>
	</div>
</div>
<!--</main>-->