<?php

namespace App\Controller;


use App\Homework\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleProvider $articleProvider)
    {
        $articles = $articleProvider->articles();
        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }
    
    /**
     * @Route("/articles/{slug}", name="app_article")
     */
    public function show($slug, ArticleProvider $articleProvider)
    {
        $article = $articleProvider->article();
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
