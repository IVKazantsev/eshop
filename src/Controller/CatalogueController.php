<?php

namespace N_ONE\App\Controller;

use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;
use N_ONE\App\Model\Repository;

class CatalogueController extends BaseController
{

	public function action(string $message): void
	{
		echo $message;
	}

}