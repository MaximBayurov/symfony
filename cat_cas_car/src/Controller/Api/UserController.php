<?php

namespace App\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted("IS_AUTHENTICATED_FULLY")]
class UserController extends AbstractController
{
    #[Route('/api/v1/user', name: 'api_user')]
    public function index(): Response
    {
        return $this->json(
            $this->getUser(),
            JsonResponse::HTTP_OK,
            [],
            [
                'groups' => 'main'
            ]
        );
    }
}
