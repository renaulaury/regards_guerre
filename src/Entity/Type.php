<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titleType = null;

    #[ORM\ManyToOne(inversedBy: 'types')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ticket $ticket = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleType(): ?string
    {
        return $this->titleType;
    }

    public function setTitleType(string $titleType): static
    {
        $this->titleType = $titleType;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function __toString()
    {
        return $this->titleType;
    }
}
