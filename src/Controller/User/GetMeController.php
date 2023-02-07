<?php

namespace App\Controller\User;

use App\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

/**
 * Class GetMeController.
 *
 * @author Valentin Charbonneau <valentincharbonneau@outlook.fr>
 */
#[AsController]
class GetMeController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializerInterface,
        private Security $security
    ) {
    }

    #[Route(
        name: 'get-me',
        path: '/api/me',
        methods: ['GET'],
        defaults: [
        '_api_resource_class' => User::class,
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $user = $this->security->getUser();

        $contextBuilder = (new ObjectNormalizerContextBuilder())->withGroups('read:user')->toArray();

        return new JsonResponse($this->serializerInterface->normalize($user, 'json', $contextBuilder));
    }
}