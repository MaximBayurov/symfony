<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use App\Homework\ArticleContentProviderInterface;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Exception;

class ArticleFixtures extends BaseFixtures implements DependentFixtureInterface
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
    
    const ARTICLE_IMAGES = [
        'car1.jpg',
        'car2.jpg',
        'car3.jpeg',
    ];
    
    private ArticleContentProviderInterface $articleContent;
    
    public function __construct(ArticleContentProviderInterface $articleContent)
    {
        $this->articleContent = $articleContent;
    }
    
    /**
     * @throws Exception
     */
    public function loadData(): void
    {
        $this->createMany(
            Article::class,
            25,
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
                    ->setBody($articleContent)
                    ->setDescription(mb_strimwidth($articleContent, 0, 50, '...'))
                ;
                
                if ($this->faker->boolean(60)) {
                    $article->setPublishedAt(
                        DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
                    );
                }
                
                $article
                    ->setAuthor($this->getRandomReference(User::class))
                    ->setLikeCount($this->faker->numberBetween(0, 10))
                    ->setImageFilename($this->faker->randomElement(self::ARTICLE_IMAGES))
                    ->setKeywords(
                        $this->faker->randomElements(
                            self::KEYWORDS,
                            $this->faker->numberBetween(0, count(self::KEYWORDS) - 1)
                        )
                    )
                ;
    
                for ($i = 0; $i < $this->faker->numberBetween(0, 5); $i++) {
                    $tag = $this->getRandomReference(Tag::class);
                    $article->addTag($tag);
                }
            }
        );
    }
    
    public function getDependencies()
    {
        return [
            TagFixtures::class,
            UserFixtures::class,
        ];
    }
}
