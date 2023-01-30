<?php

namespace App\Controller\Security;

use App\Entity\User\User;
use App\Entity\Security\Authenticator;
use App\Repository\User\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Controller\Security\Service\UserJwtGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

/**
 * Class AuthenticatorLoginController.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[AsController]
class AuthenticatorLoginController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private SerializerInterface $serializerInterface,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $passwordHasher,
        private UserJwtGeneratorInterface $userJwtGeneratorInterface
    ) {
    }

    #[Route(
        name: 'login-token',
        path: '/api/authenticator/jwt-auth',
        methods: ['POST'],
        defaults: [
        '_api_resource_class' => Authenticator::class,
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $data = (array) json_decode($this->requestStack->getCurrentRequest()->getContent());

        if (!array_key_exists("email", $data) || !array_key_exists("password", $data)) {
            $result = ["code" => 422, "message" => []];
            if (!array_key_exists("email", $data)) {
                $result["message"]["email"] = ["field" => "email", "message" => "This field is required"];
            }
            if (!array_key_exists("password", $data)) {
                $result["message"]["password"] = ["field" => "password", "message" => "This field is required"];
            }
            return new JsonResponse($result, 422);
        }

        if (!count($this->userRepository->findBy(["email" => $data["email"]]))) {
            return new JsonResponse(["code" => 404, "message" => "User not found"], 404);
        }

        $user = $this->userRepository->findOneBy(["email" => $data["email"]]);

        if (!$this->passwordHasher->isPasswordValid($user, $data["password"])) {
            return new JsonResponse(["code" => 401, "message" => "Bad credentials"], 401);
        }

        $this->userJwtGeneratorInterface->generate($user);

        $authenticator = new Authenticator();
        $authenticator->setToken($this->userJwtGeneratorInterface->getToken());
        $authenticator->setExpire($this->userJwtGeneratorInterface->getExpire());

        $contextBuilder = (new ObjectNormalizerContextBuilder())->withGroups('auth:jwt')->toArray();

        return new JsonResponse($this->serializerInterface->normalize($authenticator, 'json', $contextBuilder));
    }
}
