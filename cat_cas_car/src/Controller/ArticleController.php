<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     *
     * @param ArticleRepository $repository
     * @return Response
     */
    public function homepage(
        ArticleRepository $repository
    ): Response
    {
        $articles = $repository->findLatestPublished();
    
        $banner = rand(0, 1)
            ? 'images/cat-banner1.jpg'
            : 'images/cat-banner.jpg'
        ;
        
        return $this->render('articles/homepage.html.twig', [
            'articles' => $articles,
            'banner' => $banner,
        ]);
    }
    
    /**
     * @Route("/articles/{slug}", name="app_article_show")
     *
     * @param SlackClient $slackClient
     * @param $slug
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function show(
        $slug,
        SlackClient $slackClient,
        ArticleRepository $articleRepository
    ): Response {
    
        $article = $articleRepository->findBySlug($slug);
    
        if(!$article) {
            throw $this->createNotFoundException(
                sprintf('Статья: %s не найдена', $article->getSlug())
            );
        }
        
        if ($article->getSlug() === 'slack') {
            $slackClient->send('You can\'t see me, my time is now!');
        }
        
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }
}