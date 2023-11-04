<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Users $expediteurId = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Users $destinataireId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenuMessage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEnvoi = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpediteurId(): ?Users
    {
        return $this->expediteurId;
    }

    public function setExpediteurId(?Users $expediteurId): static
    {
        $this->expediteurId = $expediteurId;

        return $this;
    }

    public function getDestinataireId(): ?Users
    {
        return $this->destinataireId;
    }

    public function setDestinataireId(?Users $destinataireId): static
    {
        $this->destinataireId = $destinataireId;

        return $this;
    }

    public function getContenuMessage(): ?string
    {
        return $this->contenuMessage;
    }

    public function setContenuMessage(?string $contenuMessage): static
    {
        $this->contenuMessage = $contenuMessage;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(?\DateTimeInterface $dateEnvoi): static
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }
}
