<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titleTicket = null;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'ticket')]
    private Collection $orderDetails;

    /**
     * @var Collection<int, TicketPricing>
     */
    #[ORM\OneToMany(targetEntity: TicketPricing::class, mappedBy: 'ticket')]
    private Collection $ticketPricings;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->ticketPricings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleTicket(): ?string
    {
        return $this->titleTicket;
    }

    public function setTitleTicket(string $titleTicket): static
    {
        $this->titleTicket = $titleTicket;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setTicket($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getTicket() === $this) {
                $orderDetail->setTicket(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TicketPricing>
     */
    public function getTicketPricings(): Collection
    {
        return $this->ticketPricings;
    }

    public function addTicketPricing(TicketPricing $ticketPricing): static
    {
        if (!$this->ticketPricings->contains($ticketPricing)) {
            $this->ticketPricings->add($ticketPricing);
            $ticketPricing->setTicket($this);
        }

        return $this;
    }

    public function removeTicketPricing(TicketPricing $ticketPricing): static
    {
        if ($this->ticketPricings->removeElement($ticketPricing)) {
            // set the owning side to null (unless already changed)
            if ($ticketPricing->getTicket() === $this) {
                $ticketPricing->setTicket(null);
            }
        }

        return $this;
    }
}
