<?php

namespace App\Controller;

use App\Homework\ArticleContentProviderInterface;
use App\Service\SlackClient;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    const RANDOM_WORDS = [
        "эксплуатация",
        "стараться",
        "очередной",
        "быть",
        "творчество",
        "ночь",
        "миллион",
    ];
    
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
     *
     * @return Response
     * @throws Exception
     */
    public function show(
        $slug,
        SlackClient $slackClient,
        ArticleContentProviderInterface $articleContent
    ): Response {
        if ($slug === 'slack') {
            $slackClient->send('You can\'t see me, my time is now!');
        }
        
        $comments = [
            'Mortem de salvus genetrix, examinare luna!',
            'Cum assimilatio credere, omnes competitiones locus nobilis, rusticus domuses.',
            'Bilge rats are the cannibals of the addled amnesty.',
        ];
        
        $word = "";
        if (rand(0, 1) <= 0.7) {
            $wordsCount = count(self::RANDOM_WORDS);
            $wordIndex = random_int(0, $wordsCount - 1);
            $word = self::RANDOM_WORDS[$wordIndex];
        }
        $articleContent = $articleContent->get(random_int(2, 10), $word, random_int(2, 10));
        
        return $this->render('articles/show.html.twig', [
            'article' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
            'articleContent' => $articleContent,
        ]);
    }
}