<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Ignore;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Serializable;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cet email !')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $code_postal = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    #[Vich\UploadableField(mapping: 'featured_profils', fileNameProperty: 'file')]  
    #[Assert\File(maxSize:'2M', maxSizeMessage:  'La taille du fichier ne doit pas dépasser 2 Mo.')]
    private $imageFile;

    #[ORM\Column(type: 'boolean')]
    private $is_verified = false;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $resetToken;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeId = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: DemandeDeTravail::class)]
    private Collection $demandeDeTravails;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: OffreDeTravail::class)]
    private Collection $offreDeTravails;

    #[ORM\OneToMany(mappedBy: 'userIdAuteur', targetEntity: Evaluation::class)]
    private Collection $evaluations;

    #[ORM\OneToMany(mappedBy: 'expediteurId', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Notification::class)]
    private Collection $notifications;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->subscriptions = new ArrayCollection();
        $this->demandeDeTravails = new ArrayCollection();
        $this->offreDeTravails = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(?string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getImageFile(File $image = null)
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null)
    {        
        $this->imageFile = $image;

         if($image) {
            $this->file = $image->getFilename();
             $this->updated_at = new \DateTime('now');
         }

    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(?bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
    
     /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }

    public function hasActiveSubscription(): bool
    {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->isActive()) {
                return true; // L'utilisateur a au moins un abonnement actif
            }
        }

        return false; // Aucun abonnement actif trouvé
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(?string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->file,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->email,
            $this->password,
            $this->file,
            // voir la section sur le sel ci-dessous
            // $this->salt
        ] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection<int, DemandeDeTravail>
     */
    public function getDemandeDeTravails(): Collection
    {
        return $this->demandeDeTravails;
    }

    public function addDemandeDeTravail(DemandeDeTravail $demandeDeTravail): static
    {
        if (!$this->demandeDeTravails->contains($demandeDeTravail)) {
            $this->demandeDeTravails->add($demandeDeTravail);
            $demandeDeTravail->setUserId($this);
        }

        return $this;
    }

    public function removeDemandeDeTravail(DemandeDeTravail $demandeDeTravail): static
    {
        if ($this->demandeDeTravails->removeElement($demandeDeTravail)) {
            // set the owning side to null (unless already changed)
            if ($demandeDeTravail->getUserId() === $this) {
                $demandeDeTravail->setUserId(null);
            }
        }

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
            $offreDeTravail->setUserId($this);
        }

        return $this;
    }

    public function removeOffreDeTravail(OffreDeTravail $offreDeTravail): static
    {
        if ($this->offreDeTravails->removeElement($offreDeTravail)) {
            // set the owning side to null (unless already changed)
            if ($offreDeTravail->getUserId() === $this) {
                $offreDeTravail->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setUserIdAuteur($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getUserIdAuteur() === $this) {
                $evaluation->setUserIdAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setExpediteurId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getExpediteurId() === $this) {
                $message->setExpediteurId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUserId($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUserId() === $this) {
                $notification->setUserId(null);
            }
        }

        return $this;
    }
}
