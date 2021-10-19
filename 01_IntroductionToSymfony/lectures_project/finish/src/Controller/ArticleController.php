<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(Environment $twig)
    {
//        $html = $twig->render('articles/homepage.html.twig');
//        
//        return new Response($html);
    
        return $this->render('articles/homepage.html.twig');
    }

    /**
     * @Route("/articles/{slug}", name="app_article_show")
     */
    public function show($slug)
    {
        $comments = [
            'Tabes ridetiss, tanquam noster pars.',
            'Nunquam contactus galatae.',
            'Sunt acipenseres anhelare audax, nobilis impositioes.'
        ];
    
        return $this->render('articles/show.html.twig', [
            'article' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
        ]);
    }
}
