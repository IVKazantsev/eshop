<?php

/**
 * @var User $user
 * @var string $content
 */

use N_ONE\App\Model\User;
use \N_ONE\Core\Configurator;

$iconsPath = Configurator\Configurator::option('ICONS_PATH');
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="/styles/adminStyle.css">
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
		<div class="tags-container">
			<div class="tags-title">Каталог товаров</div>
			<ul class="tags">
				<li class="tag-item"><a class="tag-link" href="/admin/items">Товары</a></li>
				<li class="tag-item"><a class="tag-link" href="/admin/tags">Теги</a></li>
				<li class="tag-item"><a class="tag-link" href="/admin/orders">Заказы</a></li>
				<li class="tag-item"><a class="tag-link" href="/admin/users">Пользователи</a></li>
			</ul>
		</div>
	</div>
	<main>
		<?= $content ?>
	</main>
</body>
</html>