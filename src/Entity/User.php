<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
     /**
 * @Assert\NotBlank(message="L'email ne peut pas être vide.")
 * @Assert\Email(message="L'adresse email '{{ value }}' n'est pas une adresse email valide.")
 * @Assert\Regex(
 *     pattern="/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
 *     message="Le format de l'adresse email '{{ value }}' n'est pas valide."
 * )
 */
    private ?string $email = null;
    #[ORM\Column(length: 180)]

    /**
     * @Assert\NotBlank(message="Please enter your first name!")
     * @Assert\Length(min=2, max=50, minMessage="Your first name must be at least {{ limit }} characters long", maxMessage="Your first name cannot be longer than {{ limit }} characters")
     * @Assert\Regex(
     *      pattern="/^[a-zA-ZÀ-ÿ\-']+$/",
     *      message="Your first name must contain only letters, dashes, or apostrophes"
     * )
     */
    private ?string $fname = null;

    #[ORM\Column]
    private ?bool $enabled = null;


    #[ORM\Column(length: 180)]
        /**
     * @Assert\NotBlank(message="Please enter your last name!")
     * @Assert\Length(min=2, max=50, minMessage="Your last name must be at least {{ limit }} characters long", maxMessage="Your last name cannot be longer than {{ limit }} characters")
     * @Assert\Regex(
     *      pattern="/^[a-zA-ZÀ-ÿ\-']+$/",
     *      message="Your last name must contain only letters, dashes, or apostrophes"
     * )
     */
    private ?string $lname = null;
    #[ORM\Column(length: 180)]

    /**
     * @Assert\NotBlank
     
     * @Assert\Regex(pattern="/^\d{8}$/", message="Veuillez saisir un numéro de téléphone valide (8 chiffres)")
     */
    /**
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Phone must be at least {{ limit }} characters long",
     * max=8,
     * maxMessage = "Phone must be at least {{ limit }} characters long"
     * )
     */
    private ?int $phonenumber = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password ;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): static
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): static
    {
        $this->lname = $lname;

        return $this;
    }
    public function getPhonenumber(): ?int
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(int $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

}
