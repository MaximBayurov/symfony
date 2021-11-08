<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Homework\ArticleWordsFilter;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User|null getUser()
 */
class ArticlesController extends AbstractController
{
    
    #[Route('/admin/articles', name: 'app_admin_articles')]
    #[IsGranted("ROLE_ADMIN_ARTICLE")]
    public function index(
        Request $request,
        ArticleRepository $articleRepository,
        PaginatorInterface  $paginator
    ) {
    
        $query = $request->query->get('q');
        $limit = $request->query->getInt('limit', 20);
    
        $pagination = $paginator->paginate(
            $articleRepository->findAllWithSearchQuery($query),
            $request->query->getInt('page', 1),
            $limit
        );
    
        return $this->render('admin/articles/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    #[Route('/admin/articles/create', name: 'app_admin_articles_create')]
    #[IsGranted("ROLE_ADMIN_ARTICLE")]
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        ArticleWordsFilter $filter
    ): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Article $article
             */
            $article = $form->getData();
            
            $article
                ->setPublishedAt(new \DateTimeImmutable())
            ;
    
            $filterWords = [
                'стакан',
                'клавиатура',
                'будк',
                'щенок'
            ];
            $article->setDescription(
                $filter->filter(
                    $article->getDescription(),
                    $filterWords
                )
            );
            $article->setBody(
                $filter->filter(
                    $article->getBody(),
                    $filterWords
                )
            );
            
            $manager->persist($article);
            $manager->flush();
            
            $this->addFlash('flash_message', 'Статья успешно создана');
            
            return $this->redirectToRoute('app_admin_articles');
        }
        
        return $this->render(
            'admin/articles/create.html.twig', [
                'articleForm' => $form->createView()
            ]
        );
    }
    
    #[Route('/admin/articles/{id}/edit', name: 'app_admin_articles_edit')]
    #[IsGranted("MANAGE", subject: "article")]
    public function edit(Article $article): Response
    {
        return new Response('Здесь будет страница редактирования статьи: ' . $article->getTitle());
    }
}
