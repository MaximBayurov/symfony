<?php

namespace App\EventSubscriber;

use CatCasCarSkillboxSymfony\ArticleContentProviderBundle\Event\OnBeforeWordPasteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CatCasCarArticleContentSubscriber implements EventSubscriberInterface
{
    public function onBeforeWordPaste(OnBeforeWordPasteEvent $event)
    {
        if ($event->getPosition() % 2 == 0) {
            $event->setWord('Место для вашей рекламы');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            OnBeforeWordPasteEvent::class => 'onBeforeWordPaste',
        ];
    }
}
