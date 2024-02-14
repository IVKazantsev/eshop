<?php

namespace N_ONE\App\Controller;

use Exception;
use N_ONE\App\Model\Image;
use N_ONE\App\Model\Service\ImageService;
use N_ONE\App\Model\Service\ValidationService;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\TemplateEngine\TemplateEngine;

class ImageController extends BaseController
{
	public function renderImageForm(): string
	{
		$content =  TemplateEngine::render('pages/imageForm');

		return $this->renderAdminView($content);
	}

	public function addImage($files, $post, $itemId, $isMain)
	{
		if (!isset($post) || !ValidationService::validateImage($files))
		{
			// throw new Exception('wrong add image');
			return 'some went wrong';
		}

		$targetDir = ROOT . '/public' . Configurator::option('IMAGES_PATH') . "$itemId/"; // директория для сохранения загруженных файлов
		$targetFile = $targetDir . basename($files["image"]["name"]);
		$file_extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);

		$fullSizeImageId = $this->imageRepository->add(new Image(null, $itemId,  $isMain, 1, 1200, 900, $file_extension));
		$previewImageId = $this->imageRepository->add(new Image(null, $itemId,  $isMain, 2, 640, 480, $file_extension));

		$description = $isMain ? 'main' : 'base';

		$finalFullSizePath = $targetDir . $fullSizeImageId . "_1200_900_fullsize_" . $description . ".$file_extension";
		$finalPreviewPath = $targetDir . $previewImageId . '_640_480_preview_' . $description . ".$file_extension";
		// Попытка загрузки файла на сервер
		if (move_uploaded_file($files["image"]["tmp_name"], $targetFile))
		{
			ImageService::resizeImage($targetFile, $finalFullSizePath, 1200, 900);
			ImageService::resizeImage($targetFile, $finalPreviewPath, 640, 480);
			unlink($targetFile);
			return "The file ". basename($files["image"]["name"]). " has been uploaded.";
		}
		else
		{
			return "Sorry, there was an error uploading your file.";
		}
	}
}