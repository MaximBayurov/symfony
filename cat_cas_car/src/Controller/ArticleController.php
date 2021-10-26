<?php

namespace App\Controller;

use App\Entity\Article;
use App\Homework\ArticleContentProviderInterface;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     *
     * @return Response
     */
    public function homepage(): Response
    {
        return $this->render('articles/homepage.html.twig');
    }
    
    /**
     * @Route("/articles/{slug}", name="app_article_show")
     *
     * @param $slug
     * @param SlackClient $slackClient
     * @param ArticleContentProviderInterface $articleContent
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function show(
        $slug,
        SlackClient $slackClient,
        ArticleContentProviderInterface $articleContent,
        EntityManagerInterface $entityManager
    ): Response {
        
        $repository = $entityManager->getRepository(Article::class);
        $article = $repository->findOneBy(['slug' => $slug]);
        
        if(!$article) {
            throw $this->createNotFoundException(
                sprintf('Статья: %s не найдена', $slug)
            );
        }
        
        if ($slug === 'slack') {
            $slackClient->send('You can\'t see me, my time is now!');
        }
        
        $comments = [
            'Mortem de salvus genetrix, examinare luna!',
            'Cum assimilatio credere, omnes competitiones locus nobilis, rusticus domuses.',
            'Bilge rats are the cannibals of the addled amnesty.',
        ];
        
        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }
}