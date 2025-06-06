<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $orderDateCreation = null;

    #[ORM\Column(length: 50)]
    private ?string $orderStatus = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'order_')]
    private Collection $orderDetail;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $customerFirstname = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $customerEmail = null;

    #[ORM\Column(type: Types::STRING, length: 20, unique: true, nullable: true)]
    private ?string $numberInvoice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2, nullable: true)]
    private ?string $orderTotal = null;

    public function __construct()
    {
        $this->orderDetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDateCreation(): ?\DateTimeInterface
    {
        return $this->orderDateCreation;
    }

    public function setOrderDateCreation(\DateTimeInterface $orderDateCreation): static
    {
        $this->orderDateCreation = $orderDateCreation;

        return $this;
    }

    public function getOrderDateCreationFr(): ?string
    {
        return $this->orderDateCreation?->format('d-m-y');
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetail;
    }

    public function addOrderDetail(OrderDetail $orderDetail): static
    {
        if (!$this->orderDetail->contains($orderDetail)) {
            $this->orderDetail->add($orderDetail);
            $orderDetail->setOrder($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetail->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrder() === $this) {
                $orderDetail->setOrder(null);
            }
        }

        return $this;
    }


    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerFirstname(): ?string
    {
        return $this->customerFirstname;
    }

    public function setCustomerFirstname(?string $customerFirstname): static
    {
        $this->customerFirstname = $customerFirstname;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(?string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getNumberInvoice(): ?string
    {
        return $this->numberInvoice;
    }

    public function setNumberInvoice(?string $numberInvoice): static
    {
        $this->numberInvoice = $numberInvoice;

        return $this;
    }

    public function getOrderTotal(): ?string
    {
        return $this->orderTotal;
    }

    public function setOrderTotal(?string $orderTotal): static
    {
        $this->orderTotal = $orderTotal;

        return $this;
    }

    public function __toString()
    {
        return $this->getOrderDateCreationFr() . ' - ' . $this->id;
    }
}