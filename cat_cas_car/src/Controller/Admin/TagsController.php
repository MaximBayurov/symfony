<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use App\Repository\TagRepository;
use App\Service\LimitOptions;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    #[Route('/admin/tags', name: 'admin_tags')]
    public function index(
        Request $request,
        TagRepository $tagRepository,
        PaginatorInterface  $paginator,
        LimitOptions $limitOptions
    ): Response {
    
        $query = $request->query->get('q');
        $showDeleted = $request->query->has('showDeleted');
        $limit = $request->query->getInt('limit', 20);
    
        $pagination = $paginator->paginate(
            $tagRepository->findAllWithSearchQuery($query, $showDeleted),
            $request->query->getInt('page', 1), /*page number*/
            $limit /*limit per page*/
        );
    
        return $this->render('admin/tags/index.html.twig', [
            'pagination' => $pagination,
            'title' => "Управление тегами",
            'limitOptions' => $limitOptions->get($limit)
        ]);
    }
}
