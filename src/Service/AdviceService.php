<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\AdviceRepository;
use App\Repository\CommentRepository;

class AdviceService
{
    private $adviceRepository;
    private $commentRepository;

    public function __construct(AdviceRepository $adviceRepository, CommentRepository $commentRepository)
    {
        $this->adviceRepository = $adviceRepository;
        $this->commentRepository = $commentRepository;
    }

    public function getGroupedAdvices()
    {
        $advices = $this->adviceRepository->findAll();

        return $this->groupAdvicesByStatus($advices);
    }

    public function getGroupedAdvicesByUser(User $user): array
    {
        $adviceIds = $this->getAdviceIdsFromUserComments($user);
        $advices = $this->adviceRepository->findBy(['id' => $adviceIds]);

        return $this->groupAndSortAdvices($advices);
    }

    private function getAdviceIdsFromUserComments(User $user): array
    {
        $userId = $user->getId();
        $userComments = $this->commentRepository->findBy(['user' => $userId], ['createdAt' => 'DESC']);

        $adviceIds = [];
        foreach ($userComments as $comment) {
            $commentAdvice = $comment->getCommentAdvice();
            if (null !== $commentAdvice) {
                $adviceIds[] = $commentAdvice->getId();
            }
        }

        return $adviceIds;
    }

    private function groupAndSortAdvices(array $advices): array
    {
        $groupedAdvices = [];
        foreach ($advices as $advice) {
            $statusName = $advice->getStatus()->getName();
            if (!isset($groupedAdvices[$statusName])) {
                $groupedAdvices[$statusName] = [];
            }
            $groupedAdvices[$statusName][] = $advice;
        }

        foreach ($groupedAdvices as $statusName => &$adviceGroup) {
            usort($adviceGroup, function ($advice1, $advice2) {
                $lastComment1 = $advice1->getComments()->last();
                $lastComment2 = $advice2->getComments()->last();

                if ($lastComment1 && $lastComment2) {
                    return $lastComment2->getCreatedAt() <=> $lastComment1->getCreatedAt();
                } elseif ($lastComment1) {
                    return -1;
                } elseif ($lastComment2) {
                    return 1;
                } else {
                    return 0;
                }
            });
        }

        return $groupedAdvices;
    }

    private function groupAdvicesByStatus(array $advices): array
    {
        $groupedAdvices = [];
        foreach ($advices as $advice) {
            $statusName = $advice->getStatus()->getName();
            if (!isset($groupedAdvices[$statusName])) {
                $groupedAdvices[$statusName] = [];
            }
            $groupedAdvices[$statusName][] = $advice;
        }

        return $groupedAdvices;
    }
}
