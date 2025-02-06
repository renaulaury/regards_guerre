<?php

namespace App\Entity;

use App\Repository\ShowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShowRepository::class)]
#[ORM\Table(name: '`show`')]
class Show
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    private ?Artiste $artiste = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    private ?Exhibition $exhibition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artistPhoto = null;

    #[ORM\Column(length: 50)]
    private ?string $artistJob = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $artistBio = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $artistTextArt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): static
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getExhibition(): ?Exhibition
    {
        return $this->exhibition;
    }

    public function setExhibition(?Exhibition $exhibition): static
    {
        $this->exhibition = $exhibition;

        return $this;
    }

    public function getArtistPhoto(): ?string
    {
        return $this->artistPhoto;
    }

    public function setArtistPhoto(?string $artistPhoto): static
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
}
