<?php

use N_ONE\App;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Migrator\Migrator;

require_once __DIR__ . '/../boot.php';

$dbConnection = DbConnector::getInstance();
$migrator = Migrator::getInstance();

$migrator->migrate();


// $tagRepository = new App\Model\Repository\TagRepository($dbConnection);
// $imageRepository = new App\Model\Repository\ImageRepository($dbConnection);
// $itemRepository = new App\Model\Repository\ItemRepository($dbConnection, $tagRepository, $imageRepository);
// $userRepository = new App\Model\Repository\UserRepository($dbConnection);
// $orderRepository = new App\Model\Repository\OrderRepository($dbConnection, $userRepository, $itemRepository);

// var_dump($imageRepository->getList([1, 2]));
// var_dump($itemRepository->getByIds([1])[0]->GetFullSizeImages());
// var_dump($itemRepository->getList()[0]->getPreviewImage()->getPath());
// var_dump($userRepository->getList());
// var_dump($orderRepository->getList());

// $app = App\Application::getInstance();
// $app->run();