<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USER_EMAIL', fields: ['userEmail'])]
#[UniqueEntity(fields: ['userEmail'], message: 'Cet email est déjà utilisé.')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $userEmail = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private Collection $orders;

    /**
     * @var Collection<int, Exhibition>
     */
    #[ORM\OneToMany(targetEntity: Exhibition::class, mappedBy: 'user')]
    private Collection $exhibitions;


    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userFirstname = null;


    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->exhibitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function createSlugUser(): string
    {
        $slugify = new Slugify();
        $slugSource = '';
        
        if ($this->userFirstname && $this->userName) {
            $slugSource = $this->userFirstname . '-' . $this->userName . '-' . $this->id;
        }
        
        elseif ($this->userEmail) {
            $emailParts = explode('@', $this->userEmail);
            $slugSource = $emailParts[0];
        }

        return $slugify->slugify($slugSource);
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->userEmail;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->password = null;
    }


    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exhibition>
     */
    public function getExhibitions(): Collection
    {
        return $this->exhibitions;
    }

    public function addExhibition(Exhibition $exhibition): static
    {
        if (!$this->exhibitions->contains($exhibition)) {
            $this->exhibitions->add($exhibition);
            $exhibition->setUser($this);
        }

        return $this;
    }

    public function removeExhibition(Exhibition $exhibition): static
    {
        if ($this->exhibitions->removeElement($exhibition)) {
            // set the owning side to null (unless already changed)
            if ($exhibition->getUser() === $this) {
                $exhibition->setUser(null);
            }
        }

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserFirstName(): ?string
    {
        return $this->userFirstname;
    }

    public function setUserFirstName(?string $userFirstname): static
    {
        $this->userFirstname = $userFirstname;

        return $this;
    }

    public function __toString(): string
    {
        if ($this->userFirstname && $this->userName) {
            return $this->userFirstname . ' ' . $this->userName;
        }
        
        // Sinon, forcément l'email
        $emailParts = explode('@', $this->userEmail);
        return $emailParts[0];
    }

}
