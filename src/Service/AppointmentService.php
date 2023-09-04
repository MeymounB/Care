<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;

class AppointmentService
{
    private $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getById(int $id): Appointment
    {
        return $this->appointmentRepository->find($id);
    }

    public function getGroupedAppointments(): array
    {
        $appointments = $this->appointmentRepository->findAll();

        return $this->groupAppointmentsByStatus($appointments);
    }

    public function getPendingAppointmentsGroupedByStatus(): array
    {
        $appointments = $this->appointmentRepository->findBy(['status' => 45], ['plannedAt' => 'ASC']);

        return $this->groupAppointmentsByStatus($appointments);
    }

    public function getPendingAppointments($limitResults = 0): array
    {
        if (0 == $limitResults) {
            return $this->appointmentRepository->findBy(['status' => 45], ['plannedAt' => 'ASC']);
        } else {
            return $this->appointmentRepository->findBy(['status' => 45], ['plannedAt' => 'ASC'], $limitResults);
        }
    }

    public function countPendingAppointments(): int
    {
        return $this->appointmentRepository->count(['status' => 45]);
    }

    public function getGroupedAppointmentsByBotanist(int $user_id, string $type): array
    {
        if ($type === 'Botanist') {
            $appointments = $this->appointmentRepository->findBy(['botanist' => $user_id]);
        } elseif ($type === 'Particular') {
            $appointments = $this->appointmentRepository->findBy(['particular' => $user_id]);
        }

        return $this->groupAppointmentsByStatus($appointments);
    }

    public function getAppointmentsByBotanist(int $botanistId, $limitResults = 0): array
    {
        $criteria = ['botanist' => $botanistId, 'status' => 46];
        $orderBy = ['plannedAt' => 'ASC'];

        if (0 == $limitResults) {
            return $this->appointmentRepository->findBy($criteria, $orderBy);
        } else {
            return $this->appointmentRepository->findBy($criteria, $orderBy, $limitResults);
        }
    }

    public function getAppointmentsByIndividual(int $individualId, $limitResults = 0): array
    {
        $criteria = ['particular' => $individualId, 'status' => 46];
        $orderBy = ['plannedAt' => 'ASC'];

        if (0 == $limitResults) {
            return $this->appointmentRepository->findBy($criteria, $orderBy);
        } else {
            return $this->appointmentRepository->findBy($criteria, $orderBy, $limitResults);
        }
    }

    public function getGroupedAppointmentsByParticular(int $particularId): array
    {
        $appointments = $this->appointmentRepository->findBy(['particular' => $particularId], ['plannedAt' => 'ASC']);

        return $this->groupAppointmentsByStatus($appointments);
    }

    private function groupAppointmentsByStatus(array $appointments): array
    {
        $groupedAppointments = [];
        foreach ($appointments as $appointment) {
            $statusName = $appointment->getStatus()->getName();
            if (!isset($groupedAppointments[$statusName])) {
                $groupedAppointments[$statusName] = [];
            }
            $groupedAppointments[$statusName][] = $appointment;
        }

        return $groupedAppointments;
    }
}
