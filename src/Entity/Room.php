<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le titre de la salle est obligatoire.')]
    #[Assert\Length(max: 50, message: 'Le titre de la salle ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $titleRoom = null;

    /**
     * @var Collection<int, Show>
     */
    #[ORM\OneToMany(targetEntity: Show::class, mappedBy: 'room')]
    private Collection $shows;

    public function __construct()
    {
        $this->shows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleRoom(): ?string
    {
        return $this->titleRoom;
    }

    public function setTitleRoom(string $titleRoom): static
    {
        $this->titleRoom = $titleRoom;

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
            $show->setRoom($this);
        }

        return $this;
    }

    public function removeShow(Show $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getRoom() === $this) {
                $show->setRoom(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titleRoom;
    }
}
