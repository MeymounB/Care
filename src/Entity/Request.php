<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'appointment' => Appointment::class,
    'advice' => Advice::class,
])]
#[ORM\MappedSuperclass()]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Plant::class, inversedBy: 'requests')]
    private Collection $plants;

    #[ORM\ManyToOne(targetEntity: Particular::class, inversedBy: 'requests')]
    private ?Particular $particular = null;

    #[ORM\ManyToOne(targetEntity: Botanist::class, inversedBy: 'requests')]
    private ?Botanist $botanist = null;

    #[ORM\ManyToOne(inversedBy: 'stateRequest')]
    private ?Status $status = null;

    public function __construct()
    {
        $this->plants = new ArrayCollection();
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

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @param Collection<int, Plant> $plants
     */
    public function setPlants(Collection $plants): static
    {
        $this->plants = $plants;

        return $this;
    }

    /**
     * @return Collection<int, Plant>
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    public function addPlant(Plant $plant): static
    {
        if (!$this->plants->contains($plant)) {
            $this->plants[] = $plant;
            $plant->addRequest($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): static
    {
        if ($this->plants->contains($plant)) {
            $this->plants->removeElement($plant);
            $plant->removeRequest($this);
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

    public function __toString(): string
    {
        return $this->title;
    }
}
