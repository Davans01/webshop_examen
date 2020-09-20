<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $naw;

    /**
     * @ORM\OneToMany(targetEntity=Base::class, mappedBy="user")
     */
    private $bases;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="user")
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="user")
     */
    private $memos;

    public function __construct()
    {
        $this->bases = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->memos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
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

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNaw(): ?string
    {
        return $this->naw;
    }

    public function setNaw(?string $naw): self
    {
        $this->naw = $naw;

        return $this;
    }

    /**
     * @return Collection|Base[]
     */
    public function getBases(): Collection
    {
        return $this->bases;
    }

    public function addBasis(Base $basis): self
    {
        if (!$this->bases->contains($basis)) {
            $this->bases[] = $basis;
            $basis->setUser($this);
        }

        return $this;
    }

    public function removeBasis(Base $basis): self
    {
        if ($this->bases->contains($basis)) {
            $this->bases->removeElement($basis);
            // set the owning side to null (unless already changed)
            if ($basis->getUser() === $this) {
                $basis->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setUser($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getUser() === $this) {
                $invoice->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Memo[]
     */
    public function getMemos(): Collection
    {
        return $this->memos;
    }

    public function addMemo(Memo $memo): self
    {
        if (!$this->memos->contains($memo)) {
            $this->memos[] = $memo;
            $memo->setUser($this);
        }

        return $this;
    }

    public function removeMemo(Memo $memo): self
    {
        if ($this->memos->contains($memo)) {
            $this->memos->removeElement($memo);
            // set the owning side to null (unless already changed)
            if ($memo->getUser() === $this) {
                $memo->setUser(null);
            }
        }

        return $this;
    }
}
