<?php

namespace App\Entity;

use App\Repository\AdviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AdviceRepository::class)]
class Advice extends Request
{
    #[ORM\Column]
    private ?bool $isPublic = null;

    // #[Gedmo\Slug(fields: ['title'])]
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'commentAdvice', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function getIsPublicString(): ?string
    {
        return $this->isPublic ? 'Oui' : 'Non';
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
