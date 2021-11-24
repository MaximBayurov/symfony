<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ExampleEventListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onEventHappen(RequestEvent $event)
    {
        $this->logger->info(sprintf('Было вызван обработчик события %s', __METHOD__));
    }
}
