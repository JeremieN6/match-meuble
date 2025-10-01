<?php

namespace App\Entity;

use App\Repository\FavoriteOffreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteOffreRepository::class)]
#[ORM\Table(name: 'favorite_offre', uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_user_offre_fav', columns: ['user_id', 'offre_id'])])]
class FavoriteOffre
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
    private ?OffreDeTravail $offre = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?Users { return $this->user; }
    public function setUser(?Users $user): self { $this->user = $user; return $this; }

    public function getOffre(): ?OffreDeTravail { return $this->offre; }
    public function setOffre(?OffreDeTravail $offre): self { $this->offre = $offre; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(?\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
