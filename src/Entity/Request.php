<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorMap([
    'appointment' => Appointment::class,
    'advice' => Advice::class,
])]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Plant::class, inversedBy: 'requests')]
    private Collection $plant;

    #[ORM\ManyToOne(targetEntity: Particular::class, inversedBy: 'requests')]
    private ?Particular $particular = null;

    #[ORM\ManyToOne(targetEntity: Botanist::class, inversedBy: 'requests')]
    private ?Botanist $botanist = null;

    #[ORM\ManyToOne(inversedBy: 'stateRequest')]
    private ?Status $status = null;

    public function __construct()
    {
        $this->plant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Plant>  
     */

    public function getPlant(): Collection
    {
        return $this->plant;
    }

    public function addPlant(Plant $plant): static
    {
        if (!$this->plant->contains($plant)) {
            $this->plant->add($plant);
        }

        return $this;
    }

    public function removePlant(Plant $plant): static
    {
        if ($this->plant->contains($plant)) {
            $this->plant->removeElement($plant);
        }

        return $this;
    }

    public function getParticular(): ?Particular
    {
        return $this->particular;
    }

    public function setParticular(?Particular $particular): static
    {
        $this->particular = $particular;

        return $this;
    }

    public function getBotanist(): ?Botanist
    {
        return $this->botanist;
    }

    public function setBotanist(?Botanist $botanist): static
    {
        $this->botanist = $botanist;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }
}
