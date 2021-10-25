<?php

namespace App\Controller\Articles;

use App\Homework\ArticleContentProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleContentController extends AbstractController
{
    private ArticleContentProviderInterface $contentProvider;
    
    public function __construct(ArticleContentProviderInterface $contentProvider)
    {
        $this->contentProvider = $contentProvider;
    }
    
    #[Route('/articles/article_content', name: 'app_articles_article_content', priority: 1)]
    public function generate(Request $request): Response
    {
        $paragraphs = (int)$request->query->get('paragraphs', 1);
        $paragraphs = ($paragraphs > 0) ? $paragraphs : 1;
        $word = $request->query->get('word', '');
        $wordsCount = (int)$request->query->get('wordsCount', 0);
        
        return $this->render('articles/article_content/index.html.twig', [
            'content' => $this->contentProvider->get(
                $paragraphs, $word, $wordsCount
            ),
        ]);
    }
}
