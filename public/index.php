<?php


use N_ONE\App;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Migrator\Migrator;

require_once __DIR__ . '/../boot.php';

$migrator = Migrator::getInstance();
$migrator->migrate();

$app = App\Application::getInstance();
$app->run();