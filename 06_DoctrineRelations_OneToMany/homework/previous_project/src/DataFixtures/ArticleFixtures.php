<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Homework\ArticleContentProviderInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
{
    private static $articleTitles = [
        'Что делать, если надо верстать?',
        'Facebook ест твои данные',
        'Когда пролил кофе на клавиатуру',
    ];

    private static $articleAuthors = [
        'Флекс Абсолютович',
        'Вью j эс',
        'Фронтенд Фулстэков',
        'Хэтээмэль Цеэсесович',
    ];

    private static $articleImages = [
        'article-1.jpeg',
        'article-2.jpeg',
        'article-3.jpg',
    ];
    
    
    private static $words = ['Кофе', 'Клавиатура', 'Пролить', 'Пить', 'Рукопоп'];
    
    
    /**
     * @var ArticleContentProviderInterface
     */
    private ArticleContentProviderInterface $articleContentProvider;

    public function __construct(ArticleContentProviderInterface $articleContentProvider)
    {
        $this->articleContentProvider = $articleContentProvider;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 10, function (Article $article) {
            $article
                ->setTitle($this->faker->randomElement(self::$articleTitles))
                ->setBody($this->getArticleContent())
                ->setDescription($this->faker->realText(100))
            ;

            if ($this->faker->boolean(60)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article
                ->setAuthor($this->faker->randomElement(self::$articleAuthors))
                ->setVoteCount($this->faker->numberBetween(-10, 10))
                ->setImageFilename($this->faker->randomElement(self::$articleImages))
                ->setKeywords($this->faker->realText(50))
            ;

        });
    }
    
    private function getArticleContent()
    {
        $word = null;
        $wordsCount = 0;
        $paragraphs = $this->faker->numberBetween(2, 10);

        if ($this->faker->boolean(70)) {
            $word = $this->faker->randomElement(self::$words);
            $wordsCount = $this->faker->numberBetween(2, $paragraphs * 2);
        }

        return $this->articleContentProvider->get($paragraphs, $word, $wordsCount);
    }
}
