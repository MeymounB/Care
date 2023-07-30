<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Advice;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addComment(string $content, Advice $advice, User $user): void
    {
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setCommentAdvice($advice);
        $comment->setUser($user);
        $advice->addComment($comment);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
