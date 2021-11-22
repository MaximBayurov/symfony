<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Events\ArticleCreatedEvent;
use App\Form\ArticleFormType;
use App\Homework\ArticleWordsFilter;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        ArticleWordsFilter $filter,
        FileUploader $articleFileUploader,
        EventDispatcherInterface $dispatcher
    ): Response
    {
        $form = $this->createForm(ArticleFormType::class, new Article());
    
        if ($article = $this->handleFormRequest($form, $request, $manager, $filter, $articleFileUploader)) {
            $this->addFlash('flash_message', 'Статья успешно создана');
    
            $dispatcher->dispatch(new ArticleCreatedEvent($article));
            
            return $this->redirectToRoute('app_admin_articles');
        }
    
    
        return $this->render(
            'admin/articles/create.html.twig', [
                'articleForm' => $form->createView(),
                'showError' => $form->isSubmitted(),
            ]
        );
    }
    
    #[Route('/admin/articles/{id}/edit', name: 'app_admin_articles_edit')]
    #[IsGranted("MANAGE", subject: "article")]
    public function edit(
        Article $article,
        Request $request,
        EntityManagerInterface $manager,
        ArticleWordsFilter $filter,
        FileUploader $articleFileUploader
    ): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article, [
            'enable_published_at' => true,
        ]);
    
        if ($article = $this->handleFormRequest($form, $request, $manager, $filter, $articleFileUploader)) {
            $this->addFlash('flash_message', 'Статья успешно изменена');
        
            return $this->redirectToRoute('app_admin_articles_edit', ['id' => $article->getId()]);
        }
    
        return $this->render(
            'admin/articles/edit.html.twig', [
                'articleForm' => $form->createView(),
                'showError' => $form->isSubmitted(),
            ]
        );
    }
    
    private function handleFormRequest(
        FormInterface $form,
        Request $request,
        EntityManagerInterface $manager,
        ArticleWordsFilter $filter,
        FileUploader $articleFileUploader
    ): ?Article
    {
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Article $article
             */
            $article = $form->getData();
        
            /** @var UploadedFile|null $image */
            $image = $form->get('image')->getData();
            
            if ($image) {
                $article->setImageFilename($articleFileUploader->uploadFile($image, $article->getImageFilename()));
            }
        
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
            
            return $article;
        }
    
        return null;
    }
}
