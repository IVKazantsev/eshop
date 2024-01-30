<?php

use N_ONE\App;

require_once $_SERVER['DOCUMENT_ROOT'] . '/boot.php';

$app = new App\Application();
$app->run();