<?php

namespace App\Tests\Unit;

use App\Entity\Advice;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\AdviceRepository;
use App\Repository\CommentRepository;
use App\Service\AdviceService;
use PHPUnit\Framework\TestCase;

class AdviceServiceTest extends TestCase
{
    private $adviceRepositoryMock;
    private $commentRepositoryMock;
    private AdviceService $adviceService;

    protected function setUp(): void
    {
        $this->adviceRepositoryMock = $this->createMock(AdviceRepository::class);
        $this->commentRepositoryMock = $this->createMock(CommentRepository::class);

        $this->adviceService = new AdviceService($this->adviceRepositoryMock, $this->commentRepositoryMock);
    }

    public function testCountAdvicesByUser(): void
    {
        $userMock = $this->createMock(User::class);
        $userMock->method('getId')->willReturn(1);

        $commentMock = $this->createMock(Comment::class);
        $commentMock->method('getCommentAdvice')->willReturn($this->createMock(Advice::class));

        // Supposed to return 2 comments
        $this->commentRepositoryMock->method('findBy')->willReturn([$commentMock, $commentMock]);
        // Supposed to return that this 2 comments have 2 advices
        $this->adviceRepositoryMock->method('count')->willReturn(2);

        $result = $this->adviceService->countAdvicesByUser($userMock);
        $this->assertEquals(2, $result);
    }
}
