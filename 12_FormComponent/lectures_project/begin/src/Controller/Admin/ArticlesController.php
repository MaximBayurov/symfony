<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     */
    public function create(EntityManagerInterface $em)
    {
        return new Response('Здесь будет страница создания статьи');
    }

    /**
     * @Route("/admin/articles/{id}/edit", name="app_admin_article_edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article)
    {
        return new Response('Страница редактирования статьи: ' . $article->getTitle());
    }
}
