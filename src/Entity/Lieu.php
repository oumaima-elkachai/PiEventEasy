<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
     /**
     * @Assert\Length(min=3, minMessage="Le nom doit comporter au moins {{ limit }} caractères.")
     * @Assert\NotBlank(message="Name could not be empty")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]*$/",
     *     message="Name contains only characters"
     * )
     */
    private ?string $nom = null;
   
    #[ORM\Column]
    /**
     * @Assert\NotBlank(message="Le prix est requis")
     * @Assert\Type(type="numeric", message="Le prix doit être un nombre")
     * @Assert\GreaterThan(value=0, message="Le prix doit être supérieur à zéro")
     */
    private ?float $prix = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '4')]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '4')]
    private ?string $longitude = null;


    #[ORM\Column(length: 255)]

    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
    private ?\DateTimeInterface $DateD = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateF = null;

    #[ORM\Column]
    /**
     * @Assert\NotBlank(message="La capacité est requise")
     * @Assert\Type(type="integer", message="La capacité doit être un nombre entier")
     * @Assert\Positive(message="La capacité doit être un nombre positif")
     * @Assert\GreaterThan(value=0, message="Le prix doit être supérieur à zéro")
     */
    private ?int $capacity = null;


    #[ORM\ManyToOne(targetEntity:CategoryL::class,inversedBy: 'lieus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryL $category = null;

    #[ORM\Column(length: 255)]
     /**
     * @Assert\NotBlank(message="La région est requise")
     */
    private ?string $region = null;
    #[ORM\OneToMany(targetEntity:BookingL::class,mappedBy: 'lieub')]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    /**
     * @return Collection|BookingL[]
     */

    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(BookingL $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setLieub($this);
        }

        return $this;
    }

    public function removeBooking(BookingL $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getLieub() === $this) {
                $booking->setLieub(null);
            }
        }

        return $this;
    }
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateD(): ?\DateTimeInterface
    {
        return $this->DateD;
    }

    public function setDateD(\DateTimeInterface $DateD): static
    {
        $this->DateD = $DateD;

        return $this;
    }

    public function getDateF(): ?\DateTimeInterface
    {
        return $this->DateF;
    }

    public function setDateF(\DateTimeInterface $DateF): static
    {
        $this->DateF = $DateF;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getCategory(): ?CategoryL
    {
        return $this->category;
    }

    public function setCategory(?CategoryL $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    } 
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    
}
