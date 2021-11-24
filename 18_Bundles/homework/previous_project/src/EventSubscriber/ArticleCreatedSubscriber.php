<?php

namespace App\EventSubscriber;

use App\Events\ArticleCreatedEvent;
use App\Repository\UserRepository;
use App\Service\Mailer;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(Mailer $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function onArticleCreated(ArticleCreatedEvent $event)
    {
        $user = $this->userRepository->findOneBy(['email' => 'admin@symfony.skillbox']);

        if ($user->getId() == $event->getArticle()->getAuthor()->getId()) {
            return;
        }
     
        $this->mailer->sendAdminAboutNewArticle($user, $event->getArticle());
    }

    public static function getSubscribedEvents()
    {
        return [
            ArticleCreatedEvent::class => 'onArticleCreated',
        ];
    }
}
