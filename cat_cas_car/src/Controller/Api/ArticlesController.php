<?php

namespace App\Controller\Api;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted("IS_AUTHENTICATED_FULLY")]
class ArticlesController extends AbstractController
{
    #[Route('/api/v1/articles/{id}', name: 'api_article')]
    #[IsGranted("API", subject: "article")]
    public function index(Article $article): Response
    {
        return $this->json(
            $article,
            JsonResponse::HTTP_OK,
            [],
            [
                'groups' => 'main'
            ]
        );
    }
}
