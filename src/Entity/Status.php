<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Request::class)]
    private Collection $stateRequest;

    public function __construct()
    {
        $this->stateRequest = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getStateRequest(): Collection
    {
        return $this->stateRequest;
    }

    public function addStateRequest(Request $stateRequest): static
    {
        if (!$this->stateRequest->contains($stateRequest)) {
            $this->stateRequest->add($stateRequest);
            $stateRequest->setStatus($this);
        }

        return $this;
    }

    public function removeStateRequest(Request $stateRequest): static
    {
        if ($this->stateRequest->removeElement($stateRequest)) {
            // set the owning side to null (unless already changed)
            if ($stateRequest->getStatus() === $this) {
                $stateRequest->setStatus(null);
            }
        }

        return $this;
    }
}
