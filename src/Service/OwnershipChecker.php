<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class OwnershipChecker
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function checkOwnership($entity): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user || $user !== $entity->getUser()) {
            return new JsonResponse(['message' => 'An error has occurred'], 403);
        }

        return null;
    }
}
