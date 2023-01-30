<?php

namespace App\Controller\User\Service;

use App\Entity\User\User;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserValidator.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
class UserValidator implements UserValidatorInterface
{
    private ?bool $violating;

    private ?array $violations;

    public function __construct(
        private SerializerInterface $serializerInterface,
        private ValidatorInterface $validatorInterface
    ) {
        $this->violating = null;
        $this->violations = null;
    }

    public function validate(User $user): void
    {
        $this->violating = false;

        $violations = $this->validatorInterface->validate($user);

        if (count($violations)) {
            $this->violating = true;

            $jsonViolations = $this->serializerInterface->normalize($violations, 'json');
            $jsonViolations['detail'] = preg_replace('/plainPassword/i', "password", $jsonViolations['detail']);

            foreach ($jsonViolations['violations'] as $field => $violation) {
                $jsonViolations['violations'][$field]['propertyPath'] = $violation['propertyPath'] == "plainPassword" ? "password" : $violation['propertyPath'];
                unset($jsonViolations['violations'][$field]['parameters']);
                $explodeType = explode(":", $jsonViolations['violations'][$field]['type']);
                $jsonViolations['violations'][$field]['code'] = $explodeType[count($explodeType) - 1];
                unset($jsonViolations['violations'][$field]['type']);
            }

            $this->violations = $jsonViolations;
        }
    }

    public function isViolating(): ?bool
    {
        return $this->violating;
    }

    public function getViolations(): ?array
    {
        return $this->violations;
    }
}
