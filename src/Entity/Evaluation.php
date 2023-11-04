<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Users $userIdAuteur = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Users $userIdCible = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getUserIdAuteur(): ?Users
    {
        return $this->userIdAuteur;
    }

    public function setUserIdAuteur(?Users $userIdAuteur): static
    {
        $this->userIdAuteur = $userIdAuteur;

        return $this;
    }

    public function getUserIdCible(): ?Users
    {
        return $this->userIdCible;
    }

    public function setUserIdCible(?Users $userIdCible): static
    {
        $this->userIdCible = $userIdCible;

        return $this;
    }
}
