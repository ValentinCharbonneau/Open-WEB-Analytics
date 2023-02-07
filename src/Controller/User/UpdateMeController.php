<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
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
 * Class UpdateMeController.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[AsController]
class UpdateMeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private UserRepository $userRepository,
        private SerializerInterface $serializerInterface,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $passwordHasher,
        private UserValidatorInterface $userValidatorInterface,
        private Security $security
    ) {
    }

    #[Route(
        name: 'update-me',
        path: '/api/me',
        methods: ['PUT'],
        defaults: [
        '_api_resource_class' => User::class,
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $user = $this->security->getUser();
        $requestContent = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (array_key_exists("email", $requestContent)) {
            $user->setEmail($requestContent["email"]);
        }
        if (array_key_exists("password", $requestContent)) {
            $user->setPlainPassword($requestContent["password"]);
            $user->setPassword($this->passwordHasher->hashPassword($user, $requestContent["password"]));
        } else {
            $user->setPlainPassword("P@ss0rd_9");
        }

        $this->userValidatorInterface->validate($user);

        if ($this->userValidatorInterface->isViolating()) {
            $result = ["code" => 422, "message" => $this->userValidatorInterface->getViolations()];
            return new JsonResponse($result, 422);
        }

        $this->entityManagerInterface->flush();

        $contextBuilder = (new ObjectNormalizerContextBuilder())->withGroups('read:user')->toArray();

        return new JsonResponse($this->serializerInterface->normalize($user, 'json', $contextBuilder));
    }
}