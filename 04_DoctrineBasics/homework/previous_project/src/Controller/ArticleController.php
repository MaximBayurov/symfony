<?php

namespace App\Controller;


use App\Homework\ArticleContentProviderInterface;
use App\Homework\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function show($slug, ArticleProvider $articleProvider, ArticleContentProviderInterface $articleContentProvider)
    {
        $article = $articleProvider->article();

        $word = null;
        $wordsCount = 0;
        $paragraphs = rand(2, 10);
        
        if (rand(0, 9) < 7) {
            $words = ['Кофе', 'Клавиатура', 'Пролить', 'Пить', 'Рукопоп'];
            $word = $words[rand(0, count($words) - 1)];
            $wordsCount = rand(2, $paragraphs * 2);
        }
        
        $articleContent = $articleContentProvider->get($paragraphs, $word, $wordsCount);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articleContent' => $articleContent,
        ]);
    }
}
