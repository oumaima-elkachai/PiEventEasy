<?php

namespace App\Entity;

use App\Repository\LieuRepository;
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

    
}
