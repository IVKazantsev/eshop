<?php

use N_ONE\App;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Migrator\Migrator;

require_once __DIR__ . '/../boot.php';

$dbConnection = DbConnector::getInstance();
$migrator = Migrator::getInstance();

$migrator->migrate();
$tagRepository = new App\Model\Repository\TagRepository($dbConnection);
$itemRepository = new App\Model\Repository\ItemRepository($dbConnection, $tagRepository);
$userRepository = new App\Model\Repository\UserRepository($dbConnection);
$orderRepository = new App\Model\Repository\OrderRepository($dbConnection, $userRepository, $itemRepository);
// $var = $orderRepository->getList();
// var_dump($var);

$app = App\Application::getInstance();
$app->run();

