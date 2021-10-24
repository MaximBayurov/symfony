<?php

namespace App\Controller;

use App\Homework\ArticleContentProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleContentProviderController extends AbstractController
{
    private ArticleContentProviderInterface $contentProvider;
    
    public function __construct(ArticleContentProviderInterface $contentProvider)
    {
        $this->contentProvider = $contentProvider;
    }
    
    /**
     * @Route("/api/v1/article_content/", methods={"POST"}, name="article_content")
     */
    public function articleContent(Request $request): JsonResponse
    {
        $requestData = $request->toArray();
        
        $paragraphs = key_exists('paragraphs', $requestData)
            ? (int)$requestData['paragraphs']
            : 0;
        
        $word = $requestData['word'] ?? '';
        $wordsCount = key_exists('wordsCount', $requestData)
            ? (int)$requestData['wordsCount']
            : 0;
        
        $markdown = $this->contentProvider->get($paragraphs, $word, $wordsCount);
        return $this->json([
            'text' => $markdown,
        ]);
    }
}