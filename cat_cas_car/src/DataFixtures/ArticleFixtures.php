<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Homework\ArticleContentProviderInterface;
use App\Homework\CommentContentProviderInterface;
use DateTimeImmutable;
use Exception;

class ArticleFixtures extends BaseFixtures
{
    const RANDOM_WORDS = [
        "эксплуатация",
        "стараться",
        "очередной",
        "быть",
        "творчество",
        "ночь",
        "миллион",
    ];
    
    const KEYWORDS = [
        'статья',
        'машины',
        'кошки',
        'английский',
        'русский',
        'пираты',
        'блог'
    ];
    
    const ARTICLE_TITLES = [
        'Есть ли жизнь после девятой жизни?',
        'Когда в машинах поставят лоток?',
        'В погоне за красной точкой',
        'В чем смысл жизни сосисок',
    ];
    
    const ARTICLE_AUTHORS = [
        'Николай',
        'Mr. White',
        'Барон Сосискин',
        'Сметанка',
        'Рыжик',
        'Jhon Seena'
    ];
    
    const ARTICLE_IMAGES = [
        'car1.jpg',
        'car2.jpg',
        'car3.jpeg',
    ];
    
    const COMMENT_WORDS = [
        'помещение',
        'ужас',
        'ресурс',
        'явление',
        'водка',
        'удар',
        'больница',
        'комплекс',
    ];
    
    private ArticleContentProviderInterface $articleContent;
    private CommentContentProviderInterface $commentContent;
    
    public function __construct(
        ArticleContentProviderInterface $articleContent,
        CommentContentProviderInterface $commentContent
    ) {
        $this->articleContent = $articleContent;
        $this->commentContent = $commentContent;
    }
    
    /**
     * @throws Exception
     */
    public function loadData(): void
    {
        $this->createMany(
            Article::class,
            10,
            function (Article $article) {
                
                $randomWord = null;
                if ($this->faker->boolean(70)) {
                    $randomWord = $this->faker->randomElement(self::RANDOM_WORDS);
                }
                
                $articleContent = $this->articleContent->get(
                    $this->faker->numberBetween(2, 10),
                    $randomWord,
                    $this->faker->numberBetween(5, 10)
                );
                
                $article
                    ->setTitle($this->faker->randomElement(self::ARTICLE_TITLES))
                    ->setBody($articleContent);
                
                if ($this->faker->boolean(60)) {
                    $article->setPublishedAt(
                        DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
                    );
                }
                
                $article
                    ->setAuthor($this->faker->randomElement(self::ARTICLE_AUTHORS))
                    ->setLikeCount($this->faker->numberBetween(0, 10))
                    ->setImageFilename($this->faker->randomElement(self::ARTICLE_IMAGES))
                    ->setKeywords(
                        $this->faker->randomElements(
                            self::KEYWORDS,
                            $this->faker->numberBetween(0, count(self::KEYWORDS) - 1)
                        )
                    );
                
                $commentsCount = $this->faker->numberBetween(2, 10);
                for ($k = 0; $k < $commentsCount; $k++) {
                    $this->addComment($article);
                }
            }
        );
    }
    
    /**
     * @param Article $article
     */
    private function addComment(Article $article): void
    {
        $comment = (new Comment())
            ->setAuthorName('граф Тефтелькин')
            ->setArticle($article)
            ->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        
        if ($this->faker->boolean(30)) {
            $comment->setDeletedAt($this->faker->dateTimeThisMonth);
        }
        
        $randomWord = '';
        if ($this->faker->boolean(70)) {
            $randomWord = $this->faker->randomElement(self::COMMENT_WORDS);
        }
    
        $comment->setContent(
            $this->commentContent->get(
                $randomWord,
                $this->faker->numberBetween(1, 5)
            )
        );
        
        $this->manager->persist($comment);
    }
}
