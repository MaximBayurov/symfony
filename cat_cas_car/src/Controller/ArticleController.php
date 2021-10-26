<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
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
        
        return $this->render('articles/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }
    
    /**
     * @Route("/articles/{slug}", name="app_article_show")
     *
     * @param Article $article
     * @param SlackClient $slackClient
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function show(
        Article $article,
        SlackClient $slackClient,
        EntityManagerInterface $entityManager
    ): Response {
        
        $repository = $entityManager->getRepository(Article::class);
        $article = $repository->findOneBy(['slug' => $article->getSlug()]);
        
        if(!$article) {
            throw $this->createNotFoundException(
                sprintf('Статья: %s не найдена', $article->getSlug())
            );
        }
        
        if ($article->getSlug() === 'slack') {
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