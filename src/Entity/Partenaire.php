<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;




#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $tel = null;

    #[ORM\Column]
    private ?float $don = null;
 

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'partenaire', targetEntity: Contrat::class)]
    private Collection $contratid;

    public function __construct()
    {
        $this->contratid = new ArrayCollection();
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

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDon(): ?float
    {
        return $this->don;
    }

    public function setDon(float $don): static
    {
        $this->don = $don;

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

    /**
     * @return Collection<int, Contrat>
     */
    public function getContratid(): Collection
    {
        return $this->contratid;
    }

    public function addContratid(Contrat $contratid): static
    {
        if (!$this->contratid->contains($contratid)) {
            $this->contratid->add($contratid);
            $contratid->setPartenaire($this);
        }

        return $this;
    }

    public function removeContratid(Contrat $contratid): static
    {
        if ($this->contratid->removeElement($contratid)) {
            // set the owning side to null (unless already changed)
            if ($contratid->getPartenaire() === $this) {
                $contratid->setPartenaire(null);
            }
        }

        return $this;
    }
}