<?php

namespace App\Tests\Unit;

use App\Entity\Advice;
use App\Entity\User;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CommentServiceTest extends TestCase
{
    private CommentService $commentService;
    private EntityManagerInterface $entityManagerMock;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->commentService = new CommentService($this->entityManagerMock);
    }

    public function testAddComment(): void
    {
        // Creating simulated objects
        $content = 'This is a comment.';
        $advice = new Advice();
        $user = new User();

        // Simulating the behavior of persist and flush using the mock EntityManager
        $this->entityManagerMock->expects($this->once())
            ->method('persist');
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        // Testing the addComment method
        $this->commentService->addComment($content, $advice, $user);

        // Verifying if the persist and flush operations were called
        $this->addToAssertionCount(1);
    }
}
