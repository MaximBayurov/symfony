<?php

namespace App\Homework;

class ArticleWordsFilter
{
    public function filter(
        string $text,
        array $words = []
    ): string {
        
        foreach ($words as $word) {
            $word = strtolower($word);
            $text = preg_replace('/[ ]{0,1}'.preg_quote($word).'[^ ,\.\n]*/', '', strtolower($text));
        }
        
        return trim($text);
    }
}