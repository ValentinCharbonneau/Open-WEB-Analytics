<?php

namespace App\Controller\Security;

use App\Entity\User\User;
use App\Entity\Security\Authenticator;
use App\Repository\User\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Controller\Security\Service\UserJwtGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthenticatorRefreshJwtController.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[AsController]
class AuthenticatorRefreshJwtController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private SerializerInterface $serializerInterface,
        private JWTTokenManagerInterface  $jWTTokenManagerInterface,
        private TokenStorageInterface $tokenStorageInterface,
        private UserJwtGeneratorInterface $userJwtGeneratorInterface
    ) {
    }

    #[Route(
        name: 'refresh-token',
        path: '/api/authenticator/jwt-refresh',
        methods: ['GET'],
        defaults: [
        '_api_resource_class' => Authenticator::class,
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $decodedJwtToken = $this->jWTTokenManagerInterface->decode($this->tokenStorageInterface->getToken());

        $user = $this->userRepository->findOneBy(["email" => $decodedJwtToken["username"]]);

        $this->userJwtGeneratorInterface->generate($user);

        $authenticator = new Authenticator();
        $authenticator->setToken($this->userJwtGeneratorInterface->getToken());
        $authenticator->setExpire($this->userJwtGeneratorInterface->getExpire());

        $contextBuilder = (new ObjectNormalizerContextBuilder())->withGroups('auth:jwt')->toArray();

        return new JsonResponse($this->serializerInterface->normalize($authenticator, 'json', $contextBuilder));
    }
}
