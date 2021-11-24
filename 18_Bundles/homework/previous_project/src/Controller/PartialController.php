<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartialController extends AbstractController
{
    public function lastComments(CommentRepository $commentRepository)
    {
        $comments = $commentRepository->findThreeLatest();
 
        return $this->render('partial/last_comments.html.twig', [
            'comments' => $comments,
        ]);
    }
}
