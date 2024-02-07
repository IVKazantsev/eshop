<?php

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="/styles/style.css">
	<title>BITCAR|admin</title>
</head>
<body>
<div class="login-container">
	<form action="/login" class="login-form" method="post">
		<label class="input-label" for="email">
			Email
			<input type="text" name="email" id="login-email" placeholder="Введите email...">
		</label>
		<label class="input-label" for="password">
			Password
			<input type="password" name="password" id="login-password" placeholder="Введите пароль...">
		</label>
		<div class="remember-container">
			<label class="checkbox-label" for="remember">
				<input type="checkbox" name="remember" id="login-password">
				Запомнить меня
			</label>

		</div>
		<button type="submit" class>Войти</button>
	</form>
</div>
</body>
</html>