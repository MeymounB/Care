<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\ValidMeetingDetails;
// use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[ValidMeetingDetails]
class Appointment extends Request
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $plannedAt = null;

    #[ORM\Column]
    #[ValidMeetingDetails]
    private ?bool $isPresential = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    // #[Gedmo\Slug(fields: ['title'])]
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $slug = null;

    public function getPlannedAt(): ?\DateTimeInterface
    {
        return $this->plannedAt;
    }

    public function setPlannedAt(\DateTimeInterface $plannedAt): static
    {
        $this->plannedAt = $plannedAt;

        return $this;
    }

    public function isIsPresential(): ?bool
    {
        return $this->isPresential;
    }

    public function setIsPresential(bool $isPresential): static
    {
        $this->isPresential = $isPresential;

        return $this;
    }

    public function getIsPresential(): ?bool
    {
        return $this->isPresential;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }
}
