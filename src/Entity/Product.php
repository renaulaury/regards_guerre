<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titleProduct = null;

    #[ORM\Column(length: 255)]
    private ?string $imageProduct = null;

    #[ORM\Column(length: 255)]
    private ?string $imageProductAlt = null;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\OneToMany(targetEntity: Type::class, mappedBy: 'product')]
    private Collection $types;

   
    /**
     * @var Collection<int, ProductPricing>
     */
    #[ORM\OneToMany(targetEntity: ProductPricing::class, mappedBy: 'product')]
    private Collection $productPricings;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'product')]
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

    public function getTitleProduct(): ?string
    {
        return $this->titleProduct;
    }

    public function setTitleProduct(string $titleProduct): static
    {
        $this->titleProduct = $titleProduct;

        return $this;
    }

    public function getImageProduct(): ?string
    {
        return $this->imageProduct;
    }

    public function setImageProduct(string $imageProduct): static
    {
        $this->imageProduct = $imageProduct;

        return $this;
    }

    public function getImageProductAlt(): ?string
    {
        return $this->imageProductAlt;
    }

    public function setImageProductAlt(string $imageProductAlt): static
    {
        $this->imageProductAlt = $imageProductAlt;

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
            $type->setProduct($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getProduct() === $this) {
                $type->setProduct(null);
            }
        }

        return $this;
    }
   

    
    /**
     * @return Collection<int, ProductPricing>
     */
    public function getProductPricings(): Collection
    {
        return $this->productPricings;
    }

    public function addProductPricing(ProductPricing $productPricing): static
    {
        if (!$this->productPricings->contains($productPricing)) {
            $this->productPricings->add($productPricing);
            $productPricing->setProduct($this);
        }

        return $this;
    }

    public function removeProductPricing(ProductPricing $productPricing): static
    {
        if ($this->productPricings->removeElement($productPricing)) {
            // set the owning side to null (unless already changed)
            if ($productPricing->getProduct() === $this) {
                $productPricing->setProduct(null);
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
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $this->titleProduct;
    }

}
