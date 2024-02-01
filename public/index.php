<?php

use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\Core\Migrator\Migrator;
use N_ONE\App\Model\Repository\ItemRepository;

require_once __DIR__ . '/../boot.php';

$dbConnection = new DbConnector();
$migrator = new Migrator($dbConnection);

$migrator->migrate();

$itemRepository = new ItemRepository($dbConnection);
$item = $itemRepository->getById(1);



$newItem = new \N_ONE\App\Model\Item(4, 'BMW 4', true, 5000000, 'good car', $item->getTags());
$itemRepository->update($newItem);