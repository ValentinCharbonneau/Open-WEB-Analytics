<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Controller\User\Service\UserValidatorInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

/**
 * Class CreateUserController.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[AsController]
class CreateUserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private UserRepository $userRepository,
        private SerializerInterface $serializerInterface,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $passwordHasher,
        private UserValidatorInterface $userValidatorInterface,
    ) {
    }

    #[Route(
        name: 'admin-create-user',
        path: '/api/users',
        methods: ['POST'],
        defaults: [
        '_api_resource_class' => User::class,
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $user = new User();

        $requestContent = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (array_key_exists("email", $requestContent)) {
            $user->setEmail($requestContent["email"]);
        }
        if (array_key_exists("password", $requestContent)) {
            $user->setPlainPassword($requestContent["password"]);
            $user->setPassword($this->passwordHasher->hashPassword($user, $requestContent["password"]));
        }
        if (array_key_exists("roles", $requestContent)) {
            $user->setRoles($requestContent["roles"]);
        }

        $this->userValidatorInterface->validate($user);

        if ($this->userValidatorInterface->isViolating()) {
            $result = ["code" => 422, "message" => $this->userValidatorInterface->getViolations()];
            return new JsonResponse($result, 422);
        }

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $contextBuilder = (new ObjectNormalizerContextBuilder())->withGroups('admin:read:user')->toArray();

        return new JsonResponse($this->serializerInterface->normalize($user, 'json', $contextBuilder));
    }
}