<?php

namespace App\Entity\Security;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\Security\AuthenticatorLoginController;
use App\Controller\Security\AuthenticatorRegisterController;
use App\Controller\Security\AuthenticatorRefreshJwtController;

/**
 * Class Authenticator.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[ApiResource(
    routePrefix: '/authenticator',
    operations: [
        new Post(
            name: 'register',
            uriTemplate: '/register',
            denormalizationContext: ['groups' => ['auth:user']],
            controller: AuthenticatorRegisterController::class
        ),
        new Post(
            name: 'login-token',
            uriTemplate: '/jwt-auth',
            denormalizationContext: ['groups' => ['auth:user']],
            controller: AuthenticatorLoginController::class
        ),
        new Get(
            name: 'refresh-token',
            uriTemplate: '/jwt-refresh',
            denormalizationContext: ['groups' => ['auth:refresh']],
            controller: AuthenticatorRefreshJwtController::class
        )
    ],
    normalizationContext: ['groups' => ['auth:jwt']]
)]
class Authenticator
{
    #[Groups(['auth:jwt'])]
    private ?string $token;

    #[Groups(['auth:jwt'])]
    private ?int $expire;

    #[Groups(['auth:user'])]
    #[ApiProperty(example: "user@owa.fr")]
    private ?string $email;

    #[Groups(['auth:user'])]
    #[ApiProperty(example: "P@ss0rd_9")]
    private ?string $password;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpire(): ?int
    {
        return $this->expire;
    }

    public function setExpire(?int $expire): self
    {
        $this->expire = $expire;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
