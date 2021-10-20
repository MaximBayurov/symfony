<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @Route("/articles/{id<\d+>}/like/{type<like|dislike>}", methods={"POST"}, name="app_article_like")
     *
     */
    public function like($id, $type, LoggerInterface $logger): JsonResponse
    {
        if ($type === 'like') {
            $likes = rand(121, 300);
            $logger->info('Произошёл лайк!');
        } else {
            $likes = rand(0, 119);
            $logger->info('Произошла открутка!');
        }
        
        return $this->json([
            'likes' => $likes,
        ]);
    }
}