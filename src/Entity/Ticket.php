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

    #[ORM\Column(length: 255)]
    private ?string $imageTicket = null;

    #[ORM\Column(length: 255)]
    private ?string $imageTicketAlt = null;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\OneToMany(targetEntity: Type::class, mappedBy: 'ticket')]
    private Collection $types;

   
    /**
     * @var Collection<int, TicketPricing>
     */
    #[ORM\OneToMany(targetEntity: TicketPricing::class, mappedBy: 'ticket')]
    private Collection $productPricings;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'ticket')]
    private Collection $orderDetails;


    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->productPricings = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
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

    public function getImageTicket(): ?string
    {
        return $this->imageTicket;
    }

    public function setImageTicket(string $imageTicket): static
    {
        $this->imageTicket = $imageTicket;

        return $this;
    }

    public function getImageTicketAlt(): ?string
    {
        return $this->imageTicketAlt;
    }

    public function setImageTicketAlt(string $imageTicketAlt): static
    {
        $this->imageTicketAlt = $imageTicketAlt;

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->setTicket($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getTicket() === $this) {
                $type->setTicket(null);
            }
        }

        return $this;
    }
   

    
    /**
     * @return Collection<int, TicketPricing>
     */
    public function getTicketPricings(): Collection
    {
        return $this->productPricings;
    }

    public function addTicketPricing(TicketPricing $productPricing): static
    {
        if (!$this->productPricings->contains($productPricing)) {
            $this->productPricings->add($productPricing);
            $productPricing->setTicket($this);
        }

        return $this;
    }

    public function removeTicketPricing(TicketPricing $productPricing): static
    {
        if ($this->productPricings->removeElement($productPricing)) {
            // set the owning side to null (unless already changed)
            if ($productPricing->getTicket() === $this) {
                $productPricing->setTicket(null);
            }
        }

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
    
        public function __toString()
    {
        $this->titleTicket;
    }

}
