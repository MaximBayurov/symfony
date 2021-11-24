<?php

namespace App\Homework;


class ArticleWordsFilter
{
    public function filter($string, $words = [])
    {
        $stringWords = explode(' ', $string);
        $result = [];
        foreach ($stringWords as $word) {
            foreach ($words as $stopWord) {
                if (mb_stripos($word, $stopWord) !== false) {
                    continue(2);
                }
            }
            
            $result[] = $word;
        }
        
        return implode(' ', $result);
    }
}
