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
    private bool $debug;
    
    public function __construct(
        Parsedown $parseDown,
        AdapterInterface $cache,
        LoggerInterface $markdownLogger,
        bool $debug
    ) {
        $this->parseDown = $parseDown;
        $this->cache = $cache;
        $this->logger = $markdownLogger;
        $this->debug = $debug;
    }
    
    public function parse(string $source): string
    {
        if (stripos($source, 'красн') !== false) {
            $this->logger->info(
                'Кажется это статья о красной точке!'
            );
        }
        
        if ($this->debug) {
            return $this->parseDown->text($source);
        }
        
        return $this->cache->get(
            'markdown_' . md5($source),
            function () use ($source) {
                return $this->parseDown->text($source);
            }
        );
    }
}