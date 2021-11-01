<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use App\Service\LimitOptions;
use Carbon\Carbon;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/admin/comments', name: 'app_admin_comments')]
    public function index(
        Request $request,
        CommentRepository $commentRepository,
        PaginatorInterface  $paginator,
        LimitOptions $limitOptions
    ): Response {
    
        $query = $request->query->get('q');
        $showDeleted = $request->query->has('showDeleted');
        $limit = $request->query->getInt('limit', 20);
    
        $pagination = $paginator->paginate(
            $commentRepository->findAllWithSearchQuery($query, $showDeleted),
            $request->query->getInt('page', 1),
            $limit
        );
    
        return $this->render('admin/comments/index.html.twig', [
            'pagination' => $pagination,
            'limitOptions' => $limitOptions->get($limit),
        ]);
    }
}
