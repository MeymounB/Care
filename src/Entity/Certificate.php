<?php

namespace App\Entity;

use App\Repository\CertificateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificateRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Certificate
{
    public const STATUS_REFUSED = 'Refusé';
    public const STATUS_PENDING = 'En attente';
    public const STATUS_VALIDATED = 'Validé';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)] // Usually "blob" type is used for files : type: Types::BLOB
    private $certificateFile;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'certificates')]
    private ?Botanist $botanist = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
        $this->state = self::STATUS_PENDING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public static function getPossibleStates()
    {
        return [
            self::STATUS_REFUSED,
            self::STATUS_PENDING,
            self::STATUS_VALIDATED,
        ];
    }

    public function setState(string $state): static
    {
        if (!in_array($state, self::getPossibleStates())) {
            throw new \InvalidArgumentException('Invalid status');
        }

        $this->state = $state;

        return $this;
    }

    public function getCertificateFile()
    {
        return $this->certificateFile;
    }

    public function setCertificateFile($certificateFile): static
    {
        $this->certificateFile = $certificateFile;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
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
}
