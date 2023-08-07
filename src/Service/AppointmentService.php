<?php

namespace App\Service;

use App\Repository\AppointmentRepository;

class AppointmentService
{
    private $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
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

    public function getGroupedAppointmentsByBotanist(int $botanistId): array
    {
        $appointments = $this->appointmentRepository->findBy(['botanist' => $botanistId]);

        return $this->groupAppointmentsByStatus($appointments);
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
