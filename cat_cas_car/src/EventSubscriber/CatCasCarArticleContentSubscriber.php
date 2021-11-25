<?php

namespace App\EventSubscriber;

use CatCasCarSkillboxSymfony\ArticleContentProviderBundle\Event\OnBeforeWordPasteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CatCasCarArticleContentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            OnBeforeWordPasteEvent::class => 'onBeforeWordPaste'
        ];
    }
    
    public function onBeforeWordPaste(OnBeforeWordPasteEvent $event): void
    {
        if($event->getPosition() % 2 === 0) {
            $event->setWord('Your ads place');
        }
    }
}