<?php

namespace App\Controller\Security;

use App\Entity\User\User;
use App\Entity\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Controller\User\Service\UserValidatorInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Controller\Security\Service\UserJwtGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

/**
 * Class AuthenticatorRegisterController.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[AsController]
class AuthenticatorRegisterController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private SerializerInterface $serializerInterface,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $passwordHasher,
        private UserValidatorInterface $userValidatorInterface,
        private UserJwtGeneratorInterface $userJwtGeneratorInterface
    )
    {
    }

    #[Route(
        name: 'register',
        path: '/api/authenticator/register',
        methods: ['POST'],
        defaults: [
            '_api_resource_class' => Authenticator::class,
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $authenticator = $this->serializerInterface->deserialize($this->requestStack->getCurrentRequest()->getContent(), Authenticator::class, 'json', (new ObjectNormalizerContextBuilder())->withGroups('auth:user')->toArray());

        $user = new User();
        $user->setEmail($authenticator->getEmail());
        $user->setPlainPassword($authenticator->getPassword());
        $user->setPassword($this->passwordHasher->hashPassword($user, $authenticator->getPassword()));

        $this->userValidatorInterface->validate($user);

        if ($this->userValidatorInterface->isViolating()) {
            $result = ["code" => 422, "message" => $this->userValidatorInterface->getViolations()];
            return new JsonResponse($result, 422);
        }

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $this->userJwtGeneratorInterface->generate($user);

        $authenticator->setExpire($this->userJwtGeneratorInterface->getExpire());
        $authenticator->setToken($this->userJwtGeneratorInterface->getToken());

        $contextBuilder = (new ObjectNormalizerContextBuilder())->withGroups('auth:jwt')->toArray();

        return new JsonResponse($this->serializerInterface->normalize($authenticator, 'json', $contextBuilder));
    }
}
