<?php

namespace App\Entity;

use App\Repository\ExhibitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExhibitionRepository::class)]
class Exhibition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titleExhibit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateWarBegin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateWarEnd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateExhibit = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hourBegin = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hourEnd = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionExhibit = null;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'exhibition')]
    private Collection $orderDetails;

    /**
     * @var Collection<int, TicketPricing>
     */
    #[ORM\OneToMany(targetEntity: TicketPricing::class, mappedBy: 'exhibition')]
    private Collection $ticketPricings;

    /**
     * @var Collection<int, Show>
     */
    #[ORM\OneToMany(targetEntity: Show::class, mappedBy: 'exhibition')]
    private Collection $shows;

    #[ORM\ManyToOne(inversedBy: 'exhibitions')]
    private ?User $user = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'exhibition')]
    private Collection $comments;

    #[ORM\Column(length: 255)]
    private ?string $mainImageAlt = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->ticketPricings = new ArrayCollection();
        $this->shows = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleExhibit(): ?string
    {
        return $this->titleExhibit;
    }

    public function setTitleExhibit(string $titleExhibit): static
    {
        $this->titleExhibit = $titleExhibit;

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): static
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function getDateWarBegin(): ?\DateTimeInterface
    {
        return $this->dateWarBegin;
    }

    public function setDateWarBegin(\DateTimeInterface $dateWarBegin): static
    {
        $this->dateWarBegin = $dateWarBegin;

        return $this;
    }

    public function getDateWarBeginFr()
    {
        return $this->dateWarBegin->format('Y');
         
    }

    public function getDateWarEnd(): ?\DateTimeInterface
    {
        return $this->dateWarEnd;
    }

    public function setDateWarEnd(\DateTimeInterface $dateWarEnd): static
    {
        $this->dateWarEnd = $dateWarEnd;

        return $this;
    }

    public function getDateWarEndFr()
    {
        return $this->dateWarEnd->format('Y');
         
    }

    public function getDateExhibit(): ?\DateTimeInterface
    {
        return $this->dateExhibit;
    }

    public function setDateExhibit(\DateTimeInterface $dateExhibit): static
    {
        $this->dateExhibit = $dateExhibit;

        return $this;
    }

    public function getDateExhibitFr()
    {
        return $this->dateExhibit->format('d-m-y');
         
    }

    public function getHourBegin(): ?\DateTimeInterface
    {
        return $this->hourBegin;
    }

    public function setHourBegin(\DateTimeInterface $hourBegin): static
    {
        $this->hourBegin = $hourBegin;

        return $this;
    }

    public function getHourEnd(): ?\DateTimeInterface
    {
        return $this->hourEnd;
    }

    public function setHourEnd(\DateTimeInterface $hourEnd): static
    {
        $this->hourEnd = $hourEnd;

        return $this;
    }

    public function getDescriptionExhibit(): ?string
    {
        return $this->descriptionExhibit;
    }

    public function setDescriptionExhibit(string $descriptionExhibit): static
    {
        $this->descriptionExhibit = $descriptionExhibit;

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
            $orderDetail->setExhibition($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getExhibition() === $this) {
                $orderDetail->setExhibition(null);
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
            $ticketPricing->setExhibition($this);
        }

        return $this;
    }

    public function removeTicketPricing(TicketPricing $ticketPricing): static
    {
        if ($this->ticketPricings->removeElement($ticketPricing)) {
            // set the owning side to null (unless already changed)
            if ($ticketPricing->getExhibition() === $this) {
                $ticketPricing->setExhibition(null);
            }
        }

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
            $show->setExhibition($this);
        }

        return $this;
    }

    public function removeShow(Show $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getExhibition() === $this) {
                $show->setExhibition(null);
            }
        }

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
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setExhibition($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getExhibition() === $this) {
                $comment->setExhibition(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $this->titleExhibit;
    }

    public function getMainImageAlt(): ?string
    {
        return $this->mainImageAlt;
    }

    public function setMainImageAlt(string $mainImageAlt): static
    {
        $this->mainImageAlt = $mainImageAlt;

        return $this;
    }
}
