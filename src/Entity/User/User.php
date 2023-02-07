<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use App\Entity\Site\SiteStatus;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use App\Repository\User\UserRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\User\GetMeController;
use Doctrine\Common\Collections\Collection;
use App\Controller\User\UpdateUserController;
use App\Controller\User\CreateUserController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity("email")]
#[ApiResource(
    operations: [
        new Get(
            name: 'get-me',
            uriTemplate: '/me',
            controller: GetMeController::class
        ),
        new Put(
            name: 'update-me',
            uriTemplate: '/me',
            denormalizationContext: ['groups' => ['update:user']],
        ),
        new Post(
            name: 'admin-create-user',
            uriTemplate: '/users',
            denormalizationContext: ['groups' => ['admin:write:user']],
            normalizationContext: ['groups' => ['admin:read:user']],
            security: "is_granted('ROLE_ADMIN')",
            controller: CreateUserController::class
        ),
        new GetCollection(
            name: 'admin-getall-user',
            uriTemplate: '/users',
            normalizationContext: ['groups' => ['admin:read:user']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Get(
            name: 'admin-get-user',
            uriTemplate: '/users/{email}',
            normalizationContext: ['groups' => ['admin:read:user']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            name: 'admin-update-user',
            uriTemplate: '/users/{email}',
            denormalizationContext: ['groups' => ['admin:write:user']],
            normalizationContext: ['groups' => ['admin:read:user']],
            security: "is_granted('ROLE_ADMIN')",
            controller: UpdateUserController::class
        )
    ],
    normalizationContext: ['groups' => ['read:user']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 32)]
    #[ApiProperty(identifier: false)]
    private ?string $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Email is required")]
    #[Assert\Email(message: "Invalid format")]
    #[Groups(['read:user', 'update:user', 'admin:read:user', 'admin:write:user'])]
    #[ApiProperty(identifier: true, example: "user@owa.fr")]
    private string $email;

    #[ORM\Column]
    #[Groups(['admin:read:user', 'admin:write:user'])]
    private array $roles = [];

    #[Assert\NotBlank(message: "Password is required")]
    #[Assert\Length(min: 8, minMessage: "Your password must have a minimum length of 8 characters")]
    #[Assert\Regex('/[\$\&\+\,\:\;\=\?\@\#\|\'\<\>\.\-\^\*\(\)\%\!]/', message: "Your password must contain a special character")]
    #[ApiProperty(example: "P@ss0rd_9")]
    private string $plainPassword;

    #[ORM\Column]
    #[Groups(['update:user', 'admin:write:user'])]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SiteStatus::class)]
    private Collection $siteStatuses;

    public function __construct()
    {
        $this->id = null;
        $this->siteStatuses = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setUuid(string $newUuid): self
    {
        if ($this->id == null) {
            $this->id = $newUuid;
        }

        return $this;
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
    public function getUserIdentifier(): string
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

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, SiteStatus>
     */
    public function getSiteStatuses(): Collection
    {
        return $this->siteStatuses;
    }

    public function addSiteStatus(SiteStatus $siteStatus): self
    {
        if (!$this->siteStatuses->contains($siteStatus)) {
            $this->siteStatuses->add($siteStatus);
            $siteStatus->setUser($this);
        }

        return $this;
    }

    public function removeSiteStatus(SiteStatus $siteStatus): self
    {
        if ($this->siteStatuses->removeElement($siteStatus)) {
            // set the owning side to null (unless already changed)
            if ($siteStatus->getUser() === $this) {
                $siteStatus->setUser(null);
            }
        }

        return $this;
    }
}
