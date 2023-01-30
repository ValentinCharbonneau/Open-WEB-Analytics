<?php

namespace App\Controller\User\Service;

use App\Entity\User\User;

/**
 * Interface UserValidatorInterface.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
interface UserValidatorInterface
{
    public function validate(User $user): void;

    public function isViolating(): ?bool;

    public function getViolations(): ?array;
}
