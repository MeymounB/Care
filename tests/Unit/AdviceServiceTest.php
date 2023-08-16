<?php

namespace App\Tests\Unit;

use App\Entity\Advice;
use App\Entity\Comment;
use App\Entity\Status;
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

    public function testGetGroupedAdvices() {
        // Creating advice requests
        $advice1 = new Advice();
        $status1 = new Status();
        $status1->setName('Pending');
        $advice1->setStatus($status1);

        $advice2 = new Advice();
        $status2 = new Status();
        $status2->setName('In Progress');
        $advice2->setStatus($status2);

        $advice3 = new Advice();
        $status3 = new Status();
        $status3->setName('Completed');
        $advice3->setStatus($status3);

        // Creating a mock for AdviceRepository
        $this->adviceRepositoryMock->expects($this->exactly(2))
            ->method('findAll')
            ->willReturn([$advice1, $advice2, $advice3]);

        // Testing the method with $groupByStatus = true
        $groupedAdvices = $this->adviceService->getGroupedAdvices(true);

        // Verifying if the advices are correctly grouped by status
        $this->assertArrayHasKey('Pending', $groupedAdvices);
        $this->assertArrayHasKey('In Progress', $groupedAdvices);
        $this->assertArrayHasKey('Completed', $groupedAdvices);

        // Testing the method with $groupByStatus without any parameter, thus not sorting by status
        $allAdvices = $this->adviceService->getGroupedAdvices();

        // Verifying that all advices are returned ungrouped
        $this->assertCount(3, $allAdvices);
        $this->assertSame([$advice1, $advice2, $advice3], $allAdvices);
    }

}
