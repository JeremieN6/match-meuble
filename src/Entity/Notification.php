<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Users $userId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $messageNotif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNotification = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lu = null;

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

    public function getMessageNotif(): ?string
    {
        return $this->messageNotif;
    }

    public function setMessageNotif(?string $messageNotif): static
    {
        $this->messageNotif = $messageNotif;

        return $this;
    }

    public function getDateNotification(): ?\DateTimeInterface
    {
        return $this->dateNotification;
    }

    public function setDateNotification(?\DateTimeInterface $dateNotification): static
    {
        $this->dateNotification = $dateNotification;

        return $this;
    }

    public function isLu(): ?bool
    {
        return $this->lu;
    }

    public function setLu(?bool $lu): static
    {
        $this->lu = $lu;

        return $this;
    }
}
