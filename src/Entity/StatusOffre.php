<?php

namespace App\Entity;

use App\Repository\StatusOffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusOffreRepository::class)]
class StatusOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomStatus = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: OffreDeTravail::class)]
    private Collection $offreDeTravails;

    public function __construct()
    {
        $this->offreDeTravails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStatus(): ?string
    {
        return $this->nomStatus;
    }

    public function setNomStatus(?string $nomStatus): static
    {
        $this->nomStatus = $nomStatus;

        return $this;
    }

    /**
     * @return Collection<int, OffreDeTravail>
     */
    public function getOffreDeTravails(): Collection
    {
        return $this->offreDeTravails;
    }

    public function addOffreDeTravail(OffreDeTravail $offreDeTravail): static
    {
        if (!$this->offreDeTravails->contains($offreDeTravail)) {
            $this->offreDeTravails->add($offreDeTravail);
            $offreDeTravail->setStatus($this);
        }

        return $this;
    }

    public function removeOffreDeTravail(OffreDeTravail $offreDeTravail): static
    {
        if ($this->offreDeTravails->removeElement($offreDeTravail)) {
            // set the owning side to null (unless already changed)
            if ($offreDeTravail->getStatus() === $this) {
                $offreDeTravail->setStatus(null);
            }
        }

        return $this;
    }
}
