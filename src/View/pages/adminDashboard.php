<?php

/**
 * @var User $user
 *
 */

use N_ONE\App\Model\User;
use \N_ONE\Core\Configurator;

// var_dump($user);
$iconsPath = Configurator\Configurator::option('ICONS_PATH');
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="/styles/style.css">
	<title>Document</title>
</head>
<body>
<div class="container">
	<header class="dashboard-header">
		<p><?= $user->getName() ?></p>

		<a href="/logout"><img src=<?= $iconsPath . 'close.png' ?> alt=""></a>
	</header>
	<div class="sidebar">
		<div id="dashboard-logo">
			<img src="<?= $iconsPath . 'logo.svg' ?>" alt="">
		</div>
		<ul class="tags">
			<li class="tag-item"><a class="tag-link" href="/products/1">Товары</a></li>
			<li class="tag-item"><a class="tag-link" href="/products/2">Теги</a></li>
			<li class="tag-item"><a class="tag-link" href="/products/3">Заказы</a></li>
			<li class="tag-item"><a class="tag-link" href="/products/4">Машина №4</a></li>
		</ul>
	</div>
	<main>
		<div class="notification-card">
			<h2 class="notification-title">Новый заказов</h2>
			<h3 class="notification-title">150</h3>
			<a href="" class="notification-card-button">Подробнее</a>
		</div>
	</main>
</body>
</html>