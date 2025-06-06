<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\Share\ExhibitionShareRepository;

#[ORM\Entity(repositoryClass: ExhibitionShareRepository::class)]
class Exhibition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titleExhibit = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
   private ?string $slug = null; // Propriété slug

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateWarBegin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateWarEnd = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateExhibit = null;

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
    private Collection $orderDetail;

    /**
     * @var Collection<int, Show>
     */
    #[ORM\OneToMany(targetEntity: Show::class, mappedBy: 'exhibition')]
    private Collection $shows;

    #[ORM\ManyToOne(inversedBy: 'exhibitions')]
    private ?User $user = null;
    

    #[ORM\Column(length: 255)]
    private ?string $mainImageAlt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $hookExhibit = null;

    #[ORM\Column(length: 255)]
    private ?string $subtitleExhibit = null;

    /**
     * @var Collection<int, TicketPricing>
     */
    #[ORM\OneToMany(targetEntity: TicketPricing::class, mappedBy: 'exhibition')]
    private Collection $ticketPricings;

    #[ORM\Column]
    private ?int $stockMax = null;

    #[ORM\Column]
   private ?int $stockAlert = null;

    public function __construct()
    {
        $this->orderDetail = new ArrayCollection();
        $this->shows = new ArrayCollection();
        $this->ticketPricings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMainImageAlt(): ?string
    {
        return $this->mainImageAlt;
    }

    public function setMainImageAlt(string $mainImageAlt): static
    {
        $this->mainImageAlt = $mainImageAlt;

        return $this;
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

    public function getSlug(): ?string 
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static 
    {
        $this->slug = $slug;
        return $this;
    }

    public function createSlugDateTitleExhibit(): string
    {
        $slugify = new Slugify();
        $datePart = $this->dateExhibit ? $this->dateExhibit->format('dmY') : '';
        $namePart = $this->titleExhibit;

        $slugSource = $datePart . '-' . $namePart;
        return $slugify->slugify($slugSource);
    }

    public function getSubtitleExhibit(): ?string
    {
        return $this->subtitleExhibit;
    }

    public function setSubtitleExhibit(?string $subtitleExhibit): static
    {
        $this->subtitleExhibit = $subtitleExhibit;

        return $this;
    }

    public function getHookExhibit(): ?string
    {
        return $this->hookExhibit;
    }

    public function setHookExhibit(?string $hookExhibit): static
    {
        $this->hookExhibit = $hookExhibit;

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

    public function setDateWarEnd(?\DateTimeInterface $dateWarEnd): static
    {
        $this->dateWarEnd = $dateWarEnd;

        return $this;
    }

    public function getDateWarEndFr()
    {
        if ($this->dateWarEnd !== null) {
            return $this->dateWarEnd->format('Y');
        } else {
            return null; // Ou une autre valeur par défaut, comme une chaîne vide ""
        }
         
    }

    public function getDateExhibit(): ?\DateTimeImmutable
    {
        return $this->dateExhibit;
    }

    public function setDateExhibit(\DateTimeImmutable $dateExhibit): static
    {
        $this->dateExhibit = $dateExhibit;

        return $this;
    }

    public function getDateExhibitFr()
    {
        $date = $this->dateExhibit;

        // Create a DateTimeFormatter for French locale
        $dateFormat = new \IntlDateFormatter(
                    'fr_FR', //Pays
                    \IntlDateFormatter::LONG, //Format long
                    \IntlDateFormatter::NONE); //Fuseau horaire ou heure

        return $dateFormat->format($date);         
    }

    public function getDateExhibitFile()
    {
        $date = $this->dateExhibit;

        return $date->format('Ymd');      
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

    public function getHourBeginFr()
    {
        return $this->hourBegin->format('H\hi');
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

    public function getHourEndFr()
    {
        return $this->hourEnd->format('H\hi');
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
        return $this->orderDetail;
    }

    public function addOrderDetail(OrderDetail $orderDetail): static
    {
        if (!$this->orderDetail->contains($orderDetail)) {
            $this->orderDetail->add($orderDetail);
            $orderDetail->setExhibition($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetail->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getExhibition() === $this) {
                $orderDetail->setExhibition(null);
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
     * @return Collection<int, ticketPricing>
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

    public function getStockMax(): ?int
    {
        return $this->stockMax;
    }

    public function setStockMax(int $stockMax): static
    {
        $this->stockMax = $stockMax;

        return $this;
    }

    public function getStockAlert(): ?int
    {
        return $this->stockAlert;
    }

    public function setStockAlert(int $stockAlert): static
    {
        $this->stockAlert = $stockAlert;

        return $this;
    }

    //Nb de tickets réservés
    public function getTicketsReserved(): int
    {
        $totalTickets = 0;
        foreach ($this->orderDetail as $orderDetail) {
            $totalTickets += $orderDetail->getQuantity(); 
        }
        return $totalTickets;
    }

    //Nb de tickets restants
    public function getTicketsRemaining(): int
    {
        return $this->getStockMax() - $this->getTicketsReserved();
    }



    public function __toString()
    {
        return $this->titleExhibit;
    }
 
}




