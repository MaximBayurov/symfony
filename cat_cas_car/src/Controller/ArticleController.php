<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function homepage(): Response
    {
        return new Response('Это наша первая страница на Symfony!');
    }
    
    /**
     * @Route("/articles/{slug}")
     *
     * @param $slug
     *
     * @return Response
     */
    public function show($slug): Response
    {
        $comments = [
            'Mortem de salvus genetrix, examinare luna!',
            'Cum assimilatio credere, omnes competitiones locus nobilis, rusticus domuses.',
            'Bilge rats are the cannibals of the addled amnesty.',
        ];
        
        return $this->render('articles/show.html.twig', [
            'article' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
        ]);
    }
}