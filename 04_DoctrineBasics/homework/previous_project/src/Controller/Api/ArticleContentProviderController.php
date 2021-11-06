<?php

namespace App\Controller\Api;

use App\Homework\ArticleContentProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleContentProviderController extends AbstractController
{
    /**
     * @Route("/api/v1/article_content", name="api_aricle_content_provider", methods={"POST"})
     */
    public function index(Request $request, ArticleContentProviderInterface $articleContentProvider)
    {
        $data = json_decode($request->getContent(), true);

        $paragraphs = (int) ($data['paragraphs'] ?? 0);
        $word = $data['word'] ?? null;
        $wordsCount = (int) ($data['wordsCount'] ?? 0);
        
        return $this->json([
            'text' => $articleContentProvider->get($paragraphs, $word, $wordsCount),
        ]);
    }
}
