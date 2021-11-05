<?php

namespace App\Controller;

use App\Homework\ArticleContentProviderInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArticleContentProviderController extends AbstractController
{
    private ArticleContentProviderInterface $contentProvider;
    private Security $security;
    private LoggerInterface $apiLogger;
    
    public function __construct(
        ArticleContentProviderInterface $contentProvider,
        Security $security,
        LoggerInterface $apiLogger
    )
    {
        $this->contentProvider = $contentProvider;
        $this->security = $security;
        $this->apiLogger = $apiLogger;
    }
    
    /**
     * @Route("/api/v1/article_content/", methods={"POST"}, name="article_content")
     */
    public function articleContent(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $isGrunted = $this->security->isGranted('ROLE_API');
        if ($isGrunted === false) {
            $this->apiLogger->warning(
                "На страницу зашел авторизованный пользователь с другой ролью!", [
                    'user' => $user,
                    'path' => '/api/v1/article_content/',
            ]);
            $this->denyAccessUnlessGranted('ROLE_API');
            return $this->json([])->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
        }
        
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