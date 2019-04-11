<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Username deja utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez renseignez un username !")
     * Assert\Type(type="string")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank(message="Veuillez renseignez un mot de passe !")
     * @Assert\Length(
     *     min="6", max="18",
     *     minMessage="Six caractères minimum.",
     *     maxMessage="18 caractères maximum")
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'es pas un email valide",
     *     checkMX = true)
     * @Assert\NotBlank(message="Veuillez renseignez un email !")
     *  Assert\Type(type="email")
     * @ORM\Column(type="string", length=55)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegistered;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="user")
     */
    private $userAnnonces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFavorites", mappedBy="user", cascade={"persist"})
     */
    private $userFavorites;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;




    public function __construct()
    {
        $this->userAnnonces = new ArrayCollection();
        $this->userFavorites = new ArrayCollection();
        $this->salt = md5(uniqid(rand(), true));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateRegistered(): ?\DateTimeInterface
    {
        return $this->dateRegistered;
    }

    public function setDateRegistered(\DateTimeInterface $dateRegistered): self
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getUserAnnonces(): Collection
    {
        return $this->userAnnonces;
    }

    public function addUserAnnonce(Ad $userAnnonce): self
    {
        if (!$this->userAnnonces->contains($userAnnonce)) {
            $this->userAnnonces[] = $userAnnonce;
            $userAnnonce->setUser($this);
        }

        return $this;
    }

    public function removeUserAnnonce(Ad $userAnnonce): self
    {
        if ($this->userAnnonces->contains($userAnnonce)) {
            $this->userAnnonces->removeElement($userAnnonce);
            // set the owning side to null (unless already changed)
            if ($userAnnonce->getUser() === $this) {
                $userAnnonce->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserFavorites[]
     */
    public function getUserFavorites(): Collection
    {
        return $this->userFavorites;
    }

    public function addUserFavorite(UserFavorites $userFavorite): self
    {
        if (!$this->userFavorites->contains($userFavorite)) {
            $this->userFavorites[] = $userFavorite;
            $userFavorite->setUser($this);
        }

        return $this;
    }

    public function removeUserFavorite(UserFavorites $userFavorite): self
    {
        if ($this->userFavorites->contains($userFavorite)) {
            $this->userFavorites->removeElement($userFavorite);
            // set the owning side to null (unless already changed)
            if ($userFavorite->getUser() === $this) {
                $userFavorite->setUser(null);
            }
        }

        return $this;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }




}
