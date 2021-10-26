<?php

namespace App\Controller;


use App\Homework\ArticleContentProviderInterface;
use App\Homework\ArticleProvider;
use App\Service\MarkdownParser;
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
    public function show($slug, MarkdownParser $markdownParser, ArticleProvider $articleProvider, ArticleContentProviderInterface $articleContentProvider)
    {
        $article = $articleProvider->article();

        $word = null;
        $wordCount = 0;
        $paragraphs = rand(2, 10);
        
        if (rand(0, 9) < 7) {
            $words = ['Кофе', 'Клавиатура', 'Пролить', 'Пить', 'Рукопоп'];
            $word = $words[rand(0, count($words) - 1)];
            $wordCount = rand(2, $paragraphs * 2);
        }
        
        $articleContent = $articleContentProvider->get($paragraphs, $word, $wordCount);

        $articleContent = $markdownParser->parse($articleContent);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articleContent' => $articleContent,
        ]);
    }
}
