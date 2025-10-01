<?php

namespace App\Entity;

use App\Repository\FavoriteDemandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteDemandeRepository::class)]
#[ORM\Table(name: 'favorite_demande', uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_user_demande_fav', columns: ['user_id', 'demande_id'])])]
class FavoriteDemande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemandeDeTravail $demande = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?Users { return $this->user; }
    public function setUser(?Users $user): self { $this->user = $user; return $this; }

    public function getDemande(): ?DemandeDeTravail { return $this->demande; }
    public function setDemande(?DemandeDeTravail $demande): self { $this->demande = $demande; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(?\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
