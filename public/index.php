<?php

use N_ONE\Core\DbConnection\DbConnection;
use N_ONE\Core\Migration\Migration;

require_once __DIR__ . '/../boot.php';

$dbConnection = new DbConnection();
$migrator = new Migration($dbConnection);

$migrator->migrate();