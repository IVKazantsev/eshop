<?php

/**
 * @var array $cars ;
 */

use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

$TE = new TemplateEngine(Configurator::option("VIEWS_PATH"));

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
				<?= $TE->render('components/carCard', ['car' => $car]) ?>
			<?php endforeach; ?>

		</div>
	</main>
	<footer>Created by N_ONE team 2024</footer>
</div>
</body>
</html>