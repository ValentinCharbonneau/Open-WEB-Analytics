<?php

namespace App\Controller\Security\Service;

use App\Entity\User\User;

/**
 * Interface UserJwtGeneratorInterface.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
interface UserJwtGeneratorInterface
{
    public function generate(User $user): void;

    public function getToken(): ?string;

    public function getExpire(): ?int;
}
