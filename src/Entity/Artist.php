<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\BackOffice\ArtistBORepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtistBORepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le nom de l\'artiste est obligatoire.')]
    #[Assert\Length(max: 50, message: 'Le nom de l\'artiste ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Zéèçàùïöë -]+$/i',
        message: 'Le nom de l\'artiste ne peut contenir que des lettres, des espaces et des tirets.'
    )]
    private ?string $artistName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le prénom de l\'artiste est obligatoire.')]
    #[Assert\Length(max: 50, message: 'Le prénom de l\'artiste ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Zéèçàùïöë -]+$/i',
        message: 'Le prénom de l\'artiste ne peut contenir que des lettres, des espaces et des tirets.'
    )]
    private ?string $artistFirstname = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\Length(max: 255, message: 'Le slug de l\'artiste ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'La date de naissance de l\'artiste est obligatoire.')]
    #[Assert\Type(type: \DateTimeInterface::class, message: 'La date de naissance de l\'artiste doit être une date valide.')]
    private ?\DateTimeInterface $artistBirthDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(type: \DateTimeInterface::class, message: 'La date de décès de l\'artiste doit être une date valide.')]
    #[Assert\GreaterThan(propertyPath: 'artistBirthDate', message: 'La date de décès doit être postérieure à la date de naissance.')]
    private ?\DateTimeInterface $artistDeathDate = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le métier de l\'artiste est obligatoire.')]
    #[Assert\Length(max: 100, message: 'Le métier de l\'artiste ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $artistJob = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La biographie de l\'artiste est obligatoire.')]
    private ?string $artistBio = null;

    /**
     * @var Collection<int, Show>
     */
    #[ORM\OneToMany(targetEntity: Show::class, mappedBy: 'artist')]
    private Collection $shows;

    public function __construct()
    {
        $this->shows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtistName(): ?string
    {
        return $this->artistName;
    }

    public function setArtistName(string $artistName): static
    {
        $this->artistName = $artistName;

        return $this;
    }

    public function getArtistFirstname(): ?string
    {
        return $this->artistFirstname;
    }

    public function setArtistFirstname(string $artistFirstname): static
    {
        $this->artistFirstname = $artistFirstname;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function createSlugDateIdentityArtist(): string
    {
        $slugify = new Slugify();
        $datePart = $this->artistBirthDate ? $this->artistBirthDate->format('dmY') : '';
        $namePart = $this->artistFirstname . ' ' . $this->artistName;

        $slugSource = $datePart . '-' . $namePart;
        return $slugify->slugify($slugSource);
    }

    public function getArtistBirthDate(): ?\DateTimeInterface
    {
        return $this->artistBirthDate;
    }

    public function setArtistBirthDate(\DateTimeInterface $artistBirthDate): static
    {
        $this->artistBirthDate = $artistBirthDate;

        return $this;
    }

    public function getArtistBirthDateFr()
    {
        $date = $this->artistBirthDate;

        // Format date FR
        $dateFormat = new \IntlDateFormatter(
                    'fr_FR', //Pays
                    \IntlDateFormatter::LONG, //Format long
                    \IntlDateFormatter::NONE); //Fuseau horaire ou heure

        return $dateFormat->format($date);  
         
    }

    public function getArtistDeathDate(): ?\DateTimeInterface
    {
        return $this->artistDeathDate;
    }

    public function setArtistDeathDate(?\DateTimeInterface $artistDeathDate): static
    {
        $this->artistDeathDate = $artistDeathDate;

        return $this;
    }

    public function getArtistDeathDateFr()
    {
        $date = $this->artistDeathDate;

        // Format date FR
        $dateFormat = new \IntlDateFormatter(
                    'fr_FR', //Pays
                    \IntlDateFormatter::LONG, //Format long
                    \IntlDateFormatter::NONE); //Fuseau horaire ou heure

        return $dateFormat->format($date);         
    }   

    public function getArtistJob(): ?string
    {
        return $this->artistJob;
    }

    public function setArtistJob(string $artistJob): static
    {
        $this->artistJob = $artistJob;

        return $this;
    }

    public function getArtistBio(): ?string
    {
        return $this->artistBio;
    }

    public function setArtistBio(string $artistBio): static
    {
        $this->artistBio = $artistBio;

        return $this;
    }

    /**
     * @return Collection<int, Show>
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(Show $show): static
    {
        if (!$this->shows->contains($show)) {
            $this->shows->add($show);
            $show->setArtist($this);
        }

        return $this;
    }

    public function removeShow(Show $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getArtist() === $this) {
                $show->setArtist(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->artistFirstname. ' ' .$this->artistName;
    }
}
