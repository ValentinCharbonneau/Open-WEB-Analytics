<?php

namespace App\Controller\Security\Service;

use App\Entity\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * Class UserJwtGenerator.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
class UserJwtGenerator implements UserJwtGeneratorInterface
{
    private ?string $token;
    private ?int $expire;

    public function __construct(
        private JWTEncoderInterface $JWTEncoder
    ) {
        $this->token = null;
        $this->expire = null;
    }

    public function generate(User $user): void
    {
        $this->expire = time() + 28800;
        $this->token = $this->JWTEncoder->encode(['username' => $user->getEmail(), 'roles' => $user->getRoles(), 'exp' => $this->expire]);
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getExpire(): ?int
    {
        return $this->expire;
    }
}
