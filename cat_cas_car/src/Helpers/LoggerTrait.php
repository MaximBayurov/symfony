<?php

namespace App\Helpers;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    
    private ?LoggerInterface $logger;
    
    /**
     * @param LoggerInterface $logger
     * @required
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}