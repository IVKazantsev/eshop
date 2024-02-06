<?php


use N_ONE\App;

require_once __DIR__ . '/../boot.php';

$app = App\Application::getInstance();
$app->run();