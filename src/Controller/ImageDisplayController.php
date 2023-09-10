<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ImageDisplayController extends AbstractController
{
	#[Route('/image/{imageName}', name: 'app_image_display', methods: ['GET'])]
	public function showImageAction(string $imageName): BinaryFileResponse
	{
		$response = new BinaryFileResponse(
			$this->getParameter('photos_dir')
			. '/'
			. $imageName
		);
		$response->setContentDisposition(
			ResponseHeaderBag::DISPOSITION_ATTACHMENT
		);
		return $response;
	}
}