<?php

namespace App\Entity;

use App\Repository\BookingLRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookingLRepository::class)]
class BookingL
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="Auto")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateD = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="DateD", message="La date de fin doit être postérieure à la date de début.")
     */
    private ?\DateTimeInterface $DateF = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieub = null;

    public function getId(): ?int
    {
        return $this->id;
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

    

    public function getLieub(): ?Lieu
    {
        return $this->lieub;
    }

    public function setLieub(?Lieu $lieub): static
    {
        $this->lieub = $lieub;

        return $this;
    }
}
