<?php

namespace App\Service;

use Exception;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;

class SlackClient
{
    
    private Client $slack;
    private LoggerInterface $logger;
    
    public function __construct(Client $slack, LoggerInterface $logger)
    {
        
        $this->slack = $slack;
        $this->logger = $logger;
    }
    
    public function send(
        string $text,
        string $from = 'John Seena',
        string $icon = ':ghost:'
    ): void {
        try {
            $message = $this->slack->createMessage();
            
            $message
                ->from($from)
                ->withIcon($icon)
                ->setText($text);
            
            $this->slack->sendMessage($message);
        } catch (Exception $exception) {
            $this->logger->error(
                $exception->getMessage(),
            );
        }
    }
}