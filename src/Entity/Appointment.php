<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment extends Request
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $plannedAt = null;

    #[ORM\Column]
    private ?bool $isPresential = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

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
