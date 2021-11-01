<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Homework\CommentContentProviderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    
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
    
    private CommentContentProviderInterface $commentContent;
    
    public function __construct(CommentContentProviderInterface $commentContent)
    {
        $this->commentContent = $commentContent;
    }
    
    public function loadData(): void
    {
    
        $this->createMany(
            Comment::class,
            100,
            function (Comment $comment) {
                $comment
                    ->setAuthorName($this->faker->name)
                    ->setArticle(
                        $this->getRandomReference(Article::class)
                    )
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
        );
        
        $this->manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
