<?php

namespace App\Service;

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

    public function addComment(string $content, Advice $advice): void
    {
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setCommentAdvice($advice);
        $advice->addComment($comment);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function editComment(Comment $comment, string $newContent): void
    {
        $comment->setContent($newContent);
        $this->entityManager->flush();
    }

    public function deleteComment(Comment $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
