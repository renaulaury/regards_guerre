<?php

namespace App\Entity;

use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtisteRepository::class)]
class Artiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $artistName = null;

    #[ORM\Column(length: 50)]
    private ?string $artistFirstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $artistBirthDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $artistDeathDate = null;

    #[ORM\Column(length: 255)]
    private ?string $artistPhoto = null;

    #[ORM\Column(length: 50)]
    private ?string $artistJob = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $artistBio = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $artistTextArt = null;

    /**
     * @var Collection<int, Show>
     */
    #[ORM\OneToMany(targetEntity: Show::class, mappedBy: 'artiste')]
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

    public function getArtistBirthDate(): ?\DateTimeInterface
    {
        return $this->artistBirthDate;
    }

    public function setArtistBirthDate(\DateTimeInterface $artistBirthDate): static
    {
        $this->artistBirthDate = $artistBirthDate;

        return $this;
    }

    public function getArtistDeathDate(): ?\DateTimeInterface
    {
        return $this->artistDeathDate;
    }

    public function setArtistDeathDate(\DateTimeInterface $artistDeathDate): static
    {
        $this->artistDeathDate = $artistDeathDate;

        return $this;
    }

    public function getArtistPhoto(): ?string
    {
        return $this->artistPhoto;
    }

    public function setArtistPhoto(string $artistPhoto): static
    {
        $this->artistPhoto = $artistPhoto;

        return $this;
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

    public function getArtistTextArt(): ?string
    {
        return $this->artistTextArt;
    }

    public function setArtistTextArt(string $artistTextArt): static
    {
        $this->artistTextArt = $artistTextArt;

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
            $show->setArtiste($this);
        }

        return $this;
    }

    public function removeShow(Show $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getArtiste() === $this) {
                $show->setArtiste(null);
            }
        }

        return $this;
    }
}
