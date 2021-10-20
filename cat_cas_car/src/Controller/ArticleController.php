<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
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
        return new Response(
            sprintf(
                'Будущая страница статьи: %s!',
                ucwords(str_replace('-', ' ', $slug))
            )
        );
    }
}