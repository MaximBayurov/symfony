<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @Route("/articles/{slug}/like/{type<like|dislike>}", methods={"POST"}, name="app_article_like")
     *
     */
    public function like(
        Article $article,
        $type,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        
        if ($type === 'like') {
            $article->like();
            $logger->info('Произошёл лайк!');
        } else {
            $article->dislike();
            $logger->info('Произошла открутка!');
        }
        
        $entityManager->flush();
        
        return $this->json([
            'likes' => $article->getLikeCount(),
        ]);
    }
}