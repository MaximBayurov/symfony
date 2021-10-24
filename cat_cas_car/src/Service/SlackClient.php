<?php

namespace App\Service;

use App\Helpers\LoggerTrait;
use Exception;
use Nexy\Slack\Client;

class SlackClient
{
    use LoggerTrait;
    private Client $slack;
    
    public function __construct(Client $slack)
    {
        $this->slack = $slack;
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
        }
        catch (\Http\Client\Exception | Exception $exception){
            $this->logger?->critical(
                $exception->getMessage(),
            );
        }
    }
}