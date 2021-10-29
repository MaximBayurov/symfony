<?php

namespace App\Homework;

class ArticleContentProvider implements ArticleContentProviderInterface
{
    
    const DEFAULT_PARAGRAPHS = [
        "Ubi est teres particula? Nunquam imperium index. Cum elevatus cantare, omnes devatioes contactus nobilis, fortis gloses. c. [Латинский философ](/)",
        "[Бабка-гадалка](/): The seeker has attraction, but not **everyone handles it**. When the self of heaven forgets the solitudes of the teacher, the intuition will know visitor.",
        "[Ценитель крендельков](/): Bagel tastes best with worcestershire sauce and lots of black cardamon. **BBQ soup** is just not the same without radish sprouts and dried old cabbages.",
        "Wave of a **golden madness**, love the faith! The shiny jolly roger fast desires the fish. The black cannibal quirky robs the ale. Aye, wow. ([Банда Роджера](/))",
        "[Около науки](/). When the starship reproduces for astral city, all machines examine biological, reliable cosmonauts. Go without love, and we won’t convert a ship. Red alert, carnivorous energy!"
    ];
    
    private bool $markWithBold;
    private PasteWords $pasteWords;
    
    public function __construct($markWithBold, PasteWords $pasteWords)
    {
        $this->markWithBold = $markWithBold;
        $this->pasteWords = $pasteWords;
    }
    
    public function get(int $paragraphs, string $word = null, int $wordsCount = 0): string
    {
        $markdown = "";
        
        $paragraphsCount = count(self::DEFAULT_PARAGRAPHS);
        $count = 0;
        while($count != $paragraphs) {
            $paragraphIndex = random_int(0, $paragraphsCount - 1);
            $paragraphSeparator = ($count > 0) ? "\n\n" : "";
            $markdown .= $paragraphSeparator . self::DEFAULT_PARAGRAPHS[$paragraphIndex];
            $count++;
        }
        
        if ($word && $wordsCount > 0) {
            $markdown = $this->pasteWords->paste(
                $markdown,
                $this->formatWord($word),
                $wordsCount
            );
        }
        
        return $markdown;
    }
    
    private function formatWord(string $word): string
    {
        $word = strtolower($word);
        
        if ($this->markWithBold) {
            return "**$word**";
        } else {
            return "_{$word}_";
        }
    }
}