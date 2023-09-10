<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ImageDisplayController extends AbstractController
{
    #[Route('/image/{imageName}', name: 'app_image_display', methods: ['GET'])]
    public function showImageAction(#[CurrentUser] ?User $user, string $imageName): BinaryFileResponse
    {
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Access denied');
        }

        return new BinaryFileResponse(
            $this->getParameter('photos_dir')
            .'/'
            .$imageName
        );
    }
}
