<?php

namespace App\EventSubscriber;

use App\Events\ArticleCreatedEvent;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleCreatedSub implements EventSubscriberInterface
{
    private Mailer $mailer;
    private UserRepository $userRepository;
    
    public function __construct(Mailer $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            ArticleCreatedEvent::class => 'onArticleCreated'
        ];
    }
    
    public function onArticleCreated(ArticleCreatedEvent $event)
    {
        $article = $event->getArticle();
        $user = $article->getAuthor();
        
        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            $admin = $this->userRepository->findAdmin();
            
            $this->mailer->sendNewArticleNotification($admin, $article);
        }
    }
}