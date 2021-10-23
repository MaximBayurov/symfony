<?php

namespace App\Service;

use Demontpx\ParsedownBundle\Parsedown;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownParser
{
    private Parsedown $parseDown;
    private AdapterInterface $cache;
    private LoggerInterface $logger;
    
    public function __construct( Parsedown $parseDown, AdapterInterface $cache, LoggerInterface $logger)
    {
        $this->parseDown = $parseDown;
        $this->cache = $cache;
        $this->logger = $logger;
    }
    
    public function parse(string $source): string
    {
        if(stripos($source, 'красн') !== false) {
            $this->logger->info(
                'Кажется это статья о красной точке!'
            );
        }
        
        return $this->cache->get(
            'markdown_' . md5($source),
            function () use ($source) {
                return $this->parseDown->text($source);
            }
        );
    }
}