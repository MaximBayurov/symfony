<?php

namespace App\Service;

use Demontpx\ParsedownBundle\Parsedown;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownParser
{
    private Parsedown $parseDown;
    private AdapterInterface $cache;
    private LoggerInterface $logger;
    private bool $debug;
    private Security $security;
    
    public function __construct(
        Parsedown $parseDown,
        AdapterInterface $cache,
        LoggerInterface $markdownLogger,
        bool $debug,
        Security $security
    ) {
        $this->parseDown = $parseDown;
        $this->cache = $cache;
        $this->logger = $markdownLogger;
        $this->debug = $debug;
        $this->security = $security;
    }
    
    public function parse(string $source): string
    {
        if (stripos($source, 'красн') !== false) {
            $this->logger->info(
                'Кажется это статья о красной точке!', [
                    'user' => $this->security->getUser()
                ]
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