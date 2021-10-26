<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Homework\ArticleContentProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
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
    
    #[Route('/admin/articles/create', name: 'app_admin_articles_create')]
    public function create(
        ArticleContentProviderInterface $articleContent,
        EntityManagerInterface $entityManager
    ): Response {
        $article = new Article();
        
        $word = "";
        if (rand(0, 1) <= 0.7) {
            $wordsCount = count(self::RANDOM_WORDS);
            $wordIndex = random_int(0, $wordsCount - 1);
            $word = self::RANDOM_WORDS[$wordIndex];
        }
        
        $articleContent = $articleContent->get(random_int(2, 10), $word, random_int(2, 10));
        
        $article
            ->setTitle('Когда в машинах поставят лоток?')
            ->setSlug('when-they-put-toilet-in-car-' . random_int(100, 999))
            ->setBody($articleContent);
        
        if (rand(1, 10) > 4) {
            $article->setPublishedAt(
                new \DateTimeImmutable(sprintf('-%s days', rand(0, 100)))
            );
        }
        
        $article
            ->setAuthor('Кошачий-собачий малыш Котопёс')
            ->setLikeCount(rand(0, 10))
            ->setImageFilename('car1.jpg');
    
        $entityManager->persist($article);
        $entityManager->flush();
        
        return new Response(sprintf(
            'Создана статья id: %d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
        
        return $this->render('admin/articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
        ]);
    }
}
