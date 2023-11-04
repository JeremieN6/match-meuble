<?php

namespace App\Entity;

use App\Repository\OffreDeTravailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreDeTravailRepository::class)]
class OffreDeTravail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'offreDeTravails')]
    private ?Users $userId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column(nullable: true)]
    private ?int $remuneration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebutMontage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFinMontage = null;

    #[ORM\ManyToOne(inversedBy: 'offreDeTravails')]
    private ?StatusOffre $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $evaluation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Users
    {
        return $this->userId;
    }

    public function setUserId(?Users $userId): static
    {
        $this->userId = $userId;

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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getRemuneration(): ?int
    {
        return $this->remuneration;
    }

    public function setRemuneration(?int $remuneration): static
    {
        $this->remuneration = $remuneration;

        return $this;
    }

    public function getDateDebutMontage(): ?\DateTimeInterface
    {
        return $this->dateDebutMontage;
    }

    public function setDateDebutMontage(?\DateTimeInterface $dateDebutMontage): static
    {
        $this->dateDebutMontage = $dateDebutMontage;

        return $this;
    }

    public function getDateFinMontage(): ?\DateTimeInterface
    {
        return $this->dateFinMontage;
    }

    public function setDateFinMontage(?\DateTimeInterface $dateFinMontage): static
    {
        $this->dateFinMontage = $dateFinMontage;

        return $this;
    }

    public function getStatus(): ?StatusOffre
    {
        return $this->status;
    }

    public function setStatus(?StatusOffre $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEvaluation(): ?int
    {
        return $this->evaluation;
    }

    public function setEvaluation(?int $evaluation): static
    {
        $this->evaluation = $evaluation;

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
}
