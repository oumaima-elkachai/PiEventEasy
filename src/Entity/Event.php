<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column]
    private ?int $Phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne]
    private ?CategoryE $categoryid = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Allocation::class)]
    private Collection $allocation;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne]
    private ?User $userid = null;

    public function __construct()
    {
        $this->allocation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->Phone;
    }

    public function setPhone(int $Phone): static
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCategoryid(): ?CategoryE
    {
        return $this->categoryid;
    }

    public function setCategoryid(?CategoryE $categoryid): static
    {
        $this->categoryid = $categoryid;

        return $this;
    }

    /**
     * @return Collection<int, Allocation>
     */
    public function getAllocation(): Collection
    {
        return $this->allocation;
    }

    public function addAllocation(Allocation $allocation): static
    {
        if (!$this->allocation->contains($allocation)) {
            $this->allocation->add($allocation);
            $allocation->setEvent($this);
        }

        return $this;
    }

    public function removeAllocation(Allocation $allocation): static
    {
        if ($this->allocation->removeElement($allocation)) {
            // set the owning side to null (unless already changed)
            if ($allocation->getEvent() === $this) {
                $allocation->setEvent(null);
            }
        }

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): static
    {
        $this->userid = $userid;

        return $this;
    }
}
