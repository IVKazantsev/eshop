<?php

/**
 * @var array $cars ;
 */

use N_ONE\Core\TemplateEngine\TemplateEngine;

$TE = new TemplateEngine(ROOT . '/src/View/components/');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/styles/style.css">
	<title>eshop</title>
</head>
<body>
<div class="container">
	<div class="sidebar">sidebar</div>
	<header><p>Фиг знает что сюда пихать</p></header>

	<main>
		<div class="catalogue">
			<?php foreach ($cars as $car): ?>
				<?= $TE->render('carCard', ['car' => $car]) ?>
			<?php endforeach; ?>

		</div>
	</main>
	<footer>Created by N_ONE team 2024</footer>
</div>
</body>
</html>