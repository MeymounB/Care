<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_photo = null;

    #[ORM\Column(type: Types::BLOB)]
    private $photo;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plant $id_plant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPhoto(): ?string
    {
        return $this->id_photo;
    }

    public function setIdPhoto(string $id_photo): static
    {
        $this->id_photo = $id_photo;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIdPlant(): ?Plant
    {
        return $this->id_plant;
    }

    public function setIdPlant(?Plant $id_plant): static
    {
        $this->id_plant = $id_plant;

        return $this;
    }
}
