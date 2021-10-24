<?php

namespace App\Helpers;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    
    private ?LoggerInterface $logger;
    
    /**2.7 Генерация кода: symfony/maker-bundle
     * @param LoggerInterface $logger
     * @required
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}