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

    public function __toString()
    {
        $this->artistFirstname. ' ' .$this->artistName;
    }
}
