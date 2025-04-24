<?php

namespace App\Entity;

use App\Repository\BackOffice\ShowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ShowRepository::class)]
#[ORM\Table(name: '`show`')]
class Show
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    #[Assert\NotNull(message: 'La salle doit être spécifiée.')]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    #[Assert\NotNull(message: 'L\'exposition doit être spécifiée.')]
    private ?Exhibition $exhibition = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, message: 'Le nom du fichier photo de l\'artiste ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $artistPhoto = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le texte de présentation de l\'artiste est obligatoire.')]    
    private ?string $artistTextArt = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    #[Assert\NotNull(message: 'L\'artiste doit être spécifié.')]
    private ?Artist $artist = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, message: 'La description alternative de la photo de l\'artiste ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $artistPhotoAlt = null;

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

    public function getArtistPhotoAlt(): ?string
    {
        return $this->artistPhotoAlt;
    }

    public function setArtistPhotoAlt(?string $artistPhotoAlt): static
    {
        $this->artistPhotoAlt = $artistPhotoAlt;

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

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }
}
