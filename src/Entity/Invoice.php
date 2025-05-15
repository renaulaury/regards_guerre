<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceRepository;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 20, unique: true, nullable: true)]
    private ?string $numberInvoice = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $customerFirstname = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $customerEmail = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $orderTotal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInvoice = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $invoiceDetails = [];

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderTotal(): ?string
    {
        return $this->orderTotal;
    }

    public function setOrderTotal(?string $orderTotal): static
    {
        $this->orderTotal = $orderTotal;

        return $this;
    }

    public function getDateInvoice(): ?\DateTimeInterface
    {
        return $this->dateInvoice;
    }

    public function setDateInvoice(?\DateTimeInterface $dateInvoice): static
    {
        $this->dateInvoice = $dateInvoice;

        return $this;
    }

    public function getInvoiceDetails(): ?array
    {
        return $this->invoiceDetails;
    }

    public function setInvoiceDetails(?array $invoiceDetails): static
    {
        $this->invoiceDetails = $invoiceDetails;

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

    public function __toString(): string
    {
        return $this->numberInvoice;
    }
}