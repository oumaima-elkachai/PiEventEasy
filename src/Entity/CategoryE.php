<?php

namespace App\Entity;

use App\Repository\CategoryERepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryERepository::class)]
class CategoryE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type est requis")]
    #[Assert\Type(type: "string", message: "Le type doit être une chaîne de caractères.")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]+$/",
        message: "Le type ne doit contenir que des lettres ."
    )]
    private ?string $Type = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }
}
