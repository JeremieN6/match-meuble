<?php

namespace App\Entity;

use App\Repository\AnnonceImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AnnonceImageRepository::class)]
#[Vich\Uploadable]
class AnnonceImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?OffreDeTravail $offre = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?DemandeDeTravail $demande = null;

    #[Vich\UploadableField(mapping: 'annonce_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int { return $this->id; }

    public function getOffre(): ?OffreDeTravail { return $this->offre; }
    public function setOffre(?OffreDeTravail $offre): static { $this->offre = $offre; return $this; }

    public function getDemande(): ?DemandeDeTravail { return $this->demande; }
    public function setDemande(?DemandeDeTravail $demande): static { $this->demande = $demande; return $this; }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) { $this->updatedAt = new \DateTimeImmutable(); }
    }
    public function getImageFile(): ?File { return $this->imageFile; }

    public function getImageName(): ?string { return $this->imageName; }
    public function setImageName(?string $imageName): void { $this->imageName = $imageName; }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
}
