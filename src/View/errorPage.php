<?php
/**
 * @var int $errorCode
 * @var string $errorMessage
 */

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
<div class="error-message-container">
	<h1 class="error-message"><?= $errorCode ?> <?= $errorMessage ?></h1>
	<p>Вернуться на <a href="/">главную страницу</a></p>
</div>
</body>
</html>