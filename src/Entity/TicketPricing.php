<?php

namespace App\Entity;

use App\Repository\TicketPricingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketPricingRepository::class)]
class TicketPricing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Assert\NotBlank(message: 'Le prix standard est obligatoire.')]
    #[Assert\Positive(message: 'Le prix standard doit être un nombre positif.')]    
    private ?string $standardPrice = null;

    #[ORM\ManyToOne(inversedBy: 'ticketPricings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le ticket associé doit être défini.')]
    private ?Ticket $ticket = null;

    #[ORM\ManyToOne(inversedBy: 'ticketPricings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'L\'exposition associée doit être définie.')]
    private ?Exhibition $exhibition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStandardPrice(): ?string
    {
        return $this->standardPrice;
    }

    public function setStandardPrice(string $standardPrice): static
    {
        $this->standardPrice = $standardPrice;

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

    public function getExhibition(): ?Exhibition
    {
        return $this->exhibition;
    }

    public function setExhibition(?Exhibition $exhibition): static
    {
        $this->exhibition = $exhibition;

        return $this;
    }

    public function __toString()
    {
        return $this->standardPrice;
    }

}
