<?php

namespace App\Entity;

use App\Repository\CategoryLRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryLRepository::class)]
/**
 * @UniqueEntity("nom", message="Name already exist")
 */
class CategoryL
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Name could not be empty")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]*$/",
     *     message="Name contains only characters"
     * )
     */
    private ?string $nom = null;
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Lieu::class,cascade:["all"],orphanRemoval:true)]
    private Collection $lieus;

    public function __construct()
    {
        $this->lieus = new ArrayCollection();
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
}
