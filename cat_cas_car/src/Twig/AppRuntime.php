<?php

namespace App\Twig;

use App\Service\MarkdownParser;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    private MarkdownParser $parser;
    
    public function __construct(MarkdownParser $parser)
    {
        $this->parser = $parser;
    }
    public function parseMarkdown($content): string
    {
        return $this->parser->parse($content);
    }
}