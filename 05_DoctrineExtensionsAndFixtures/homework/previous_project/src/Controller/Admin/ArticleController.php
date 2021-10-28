<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Homework\ArticleContentProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles/create", name="app_admin_articles_new")
     */
    public function create(EntityManagerInterface $em, ArticleContentProviderInterface $articleContentProvider)
    {

        $word = null;
        $wordsCount = 0;
        $paragraphs = rand(2, 10);

        if (rand(0, 9) < 7) {
            $words = ['Кофе', 'Клавиатура', 'Пролить', 'Пить', 'Рукопоп'];
            $word = $words[rand(0, count($words) - 1)];
            $wordsCount = rand(2, $paragraphs * 2);
        }
    
        $article = new Article();
        $article
            ->setTitle('Что делать, если надо верстать?')
            ->setKeywords('Верстать, непереверстать')
            ->setDescription('Toss each side of the seaweed with one container of chicken.')
            ->setSlug('what-to-do-if-need-fronted-' . rand(100, 999))
            ->setBody($articleContentProvider->get($paragraphs, $word, $wordsCount))
        ;
        
        if (rand(1, 10) > 4) {
            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(0, 100))));
        }

        $article
            ->setAuthor('Флекс Абсолютович')
            ->setVoteCount(rand(-10, 10))
            ->setImageFilename('article-2.jpeg')
        ;

        $em->persist($article);
        $em->flush();

        return new Response(sprintf(
            'Создана новая статья id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }
}
