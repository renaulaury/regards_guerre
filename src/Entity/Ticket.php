<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TicketRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titleTicket = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $slug = null; // Propriété slug

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
    private Collection $ticketPricings;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'ticket')]
    private Collection $orderDetail;


    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->ticketPricings = new ArrayCollection();
        $this->orderDetail = new ArrayCollection();
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

    public function getSlug(): ?string 
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static 
    {
        $this->slug = $slug;
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

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetail(): Collection
    {
        return $this->orderDetail;
    }

    public function addOrderDetail(OrderDetail $orderDetail): static
    {
        if (!$this->orderDetail->contains($orderDetail)) {
            $this->orderDetail->add($orderDetail);
            $orderDetail->setTicket($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetail->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getTicket() === $this) {
                $orderDetail->setTicket(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->titleTicket;
    }

}
