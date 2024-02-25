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
	// public function renderAddImagesForm($itemId): string
	// {
	// 	$content =  TemplateEngine::render('pages/addImageForm', [
	// 		'itemId' => $itemId,
	// 	]);
	//
	// 	return $this->renderAdminView($content);
	// }
	// public function renderDeleteImagesForm(int $itemId):string
	// {
	// 	$images = $this->imageRepository->getList([$itemId]);
	//
	// 	$content = TemplateEngine::render('pages/deleteImageForm', [
	// 		'images' => $images[$itemId],
	// 	]);
	//
	// 	return $this->renderAdminView($content);
	// }
	// public function deleteImages(array $imagesIds)
	// {
	// 	$imagesIds = array_map('intval', $imagesIds);
	// 	$images = $this->imageRepository->getList($imagesIds, true);
	// 	$path = ROOT . '/public' . Configurator::option('IMAGES_PATH');
	//
	// 	$this->imageRepository->permanentDeleteByIds($imagesIds);
	//
	// 	foreach ($imagesIds as $id)
	// 	{
	// 		unlink($path . $images[$id][0]->getPath());
	// 	}
	//
	// 	echo 'удаленно';
	// }
	// public function addBaseImages($files, $itemId): string
	// {
	// 	$fileCount = count($files['image']['name']);
	//
	// 	for ($i = 0; $i < $fileCount; $i++)
	// 	{
	// 		if (!ValidationService::validateImage($files, $i))
	// 		{
	// 			return 'some went wrong';
	// 		}
	// 		$targetDir = ROOT . '/public' . Configurator::option('IMAGES_PATH') . "$itemId/"; // директория для сохранения загруженных файлов
	// 		$targetFile = $targetDir . basename($files["image"]["name"][$i]);
	// 		$file_extension = pathinfo($files['image']['name'][$i], PATHINFO_EXTENSION);
	//
	// 		$fullSizeImageId = $this->imageRepository->add(new Image(null, $itemId,  false, 1, 1200, 900, $file_extension));
	// 		$previewImageId = $this->imageRepository->add(new Image(null, $itemId,  false, 2, 640, 480, $file_extension));
	//
	// 		$finalFullSizePath = $targetDir . $fullSizeImageId . "_1200_900_fullsize_base" . ".$file_extension";
	// 		$finalPreviewPath = $targetDir . $previewImageId . '_640_480_preview_base' . ".$file_extension";
	// 		// Попытка загрузки файла на сервер
	// 		if (move_uploaded_file($files["image"]["tmp_name"][$i], $targetFile))
	// 		{
	// 			ImageService::resizeImage($targetFile, $finalFullSizePath, 1200, 900);
	// 			ImageService::resizeImage($targetFile, $finalPreviewPath, 640, 480);
	// 			unlink($targetFile);
	// 		}
	// 		else
	// 		{
	// 			return "Sorry, there was an error uploading your file.";
	// 		}
	// 	}
	// 	return 'files has been uploaded';
	// }
	//TODO переименовку main image и изменение записи в бд
}

