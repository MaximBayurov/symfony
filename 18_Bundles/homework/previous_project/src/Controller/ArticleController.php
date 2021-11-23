<?php

namespace App\Controller;


use App\Entity\Article;
use App\Homework\ArticleContentProviderInterface;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findLatestPublished();
        
        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/articles/article_content", name="app_article_content")
     */
    public function articleContent(Request $request, ArticleContentProviderInterface $articleContentProvider)
    {
        $paragraphs = (int)$request->get('paragraphs');
        $word       = $request->get('word');
        $wordsCount = (int)$request->get('wordsCount');

        $articleContent = $articleContentProvider->get($paragraphs, $word, $wordsCount);

        return $this->render('article/content.html.twig', [
            'articleContent' => $articleContent,
        ]);
    }
    
    /**
     * @Route("/articles/{slug}", name="app_article")
     */
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
