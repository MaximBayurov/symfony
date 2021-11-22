<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class PartialController extends AbstractController
{
    
    public function catsKnowThis(HttpKernelInterface $httpKernel): Response
    {
        $request = new Request();
        $request->attributes->set('_controller', '\\App\\Controller\\PartialController::lastComments');
        
        /** @var JsonResponse $response */
        $response = $httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);
    
        $comments = [];
        if ($content = $response->getContent()) {
            $content = json_decode($content, true);
            $comments = $content['comments'];
        }
        
        return $this->render('partial/last_comments.html.twig', [
            'comments' => $comments,
        ]);
    }
    
    public function lastComments()
    {
        $comments = [
            [
                'comment' => 'Недавно наши ученые создали гудок против собак,
        другие коты его не слышат, зато собаки уступают дорогу. Наконец-то в лоточек без пробок',
                'author' => 'Рыжий Бесстыжий, Антикоррупционер',
            ],
            [
                'comment' => 'Этот сайт - это просто прелесть, я влюблена!<i class="fas fa-heart text-danger"></i> <i class="fas fa-heart text-danger"></i> <i class="fas fa-heart text-danger"></i>',
                'author' => 'Сметанка, Мурлыкатель',
            ],
            [
                'comment' => 'Предлагаю ввести еще темы о еде,
        например, у меня заготовлены шикарные статьи по сосискам',
                'author' => 'Барон Сосискин, Дегустатор сосисок',
            ],
        ];
        
        shuffle($comments);
        
        return $this->json([
            'comments' => $comments,
        ]);
    }
    
    public function mainBanner(Request $request)
    {
        $banner = $request->attributes->get('banner');
        
        return $this->render('partial/main_banner.html.twig', [
            'banner' => $banner,
        ]);
    }
}
