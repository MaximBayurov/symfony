<?php

namespace App\Homework;

use Faker\Factory;
use Faker\Generator;

class CommentContentProvider implements CommentContentProviderInterface
{
    
    private Generator $faker;
    private PasteWords $pasteWords;
    
    public function __construct(PasteWords $pasteWords)
    {
        $this->faker = Factory::create();
        $this->pasteWords = $pasteWords;
    }
    
    public function get(string $word = null, int $wordsCount = 0): string
    {
    
        $sentenceCount = $this->faker->numberBetween(1, 3);
        $wordsInSentence = $this->faker->numberBetween(5, 10);
        $sentences = [];
        while ($sentenceCount > 0) {
            $words = $this->faker->words($wordsInSentence);
            $words[0] = ucwords($words[0]);
            $sentences[] =  join(' ', $words) . '.';
            $sentenceCount--;
        }
        $comment =  join(' ', $sentences);
    
        return $this->pasteWords->paste($comment, $word, $wordsCount);
    }
}