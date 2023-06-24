<?php

namespace App\Entity;

use App\Repository\BotanistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BotanistRepository::class)]
class Botanist extends User
{
    #[ORM\Column]
    private ?bool $isVerified = null;

    #[ORM\OneToMany(mappedBy: 'botanist', targetEntity: Request::class)]
    private Collection $requests;

    #[ORM\OneToMany(mappedBy: 'botanist', targetEntity: Certificate::class)]
    private Collection $certificates;

    public function __construct()
    {
        parent::__construct();
        $this->requests = new ArrayCollection();
        $this->certificates = new ArrayCollection();
        $this->roles[] = 'ROLE_BOTANIST';
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setBotanist($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getBotanist() === $this) {
                $request->setBotanist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Certificate>
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    public function addCertificate(Certificate $certificate): static
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates->add($certificate);
            $certificate->setBotanist($this);
        }

        return $this;
    }

    public function removeCertificate(Certificate $certificate): static
    {
        if ($this->certificates->removeElement($certificate)) {
            // set the owning side to null (unless already changed)
            if ($certificate->getBotanist() === $this) {
                $certificate->setBotanist(null);
            }
        }

        return $this;
    }
}
