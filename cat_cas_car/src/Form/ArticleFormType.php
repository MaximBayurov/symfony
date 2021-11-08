<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Clue\StreamFilter\fun;

class ArticleFormType extends AbstractType
{
    private UserRepository $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Укажите название статьи',
                'help' => 'Не используйте в названии слово "собака"',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание статьи',
                'attr' => [
                    'rows' => 3
                ]
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Содержимое статьи',
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('publishedAt', null, [
                'widget' => 'single_text',
                'label' => 'Дата публикации статьи',
            ])
            ->add('keywords', TextType::class, [
                'label' => 'Ключевые слова статьи',
                'help' => 'Указывайте слова через запятую',
            ])
            ->add('author', EntityType::class, [
                'label' => 'Автор статьи',
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return sprintf('%s (id: %d)', $user->getFirstName(), $user->getId());
                },
                'placeholder' => 'Выберите автора статьи',
                'choices' => $this->userRepository->findAllSortedByName()
            ])
        ;
        
        $builder->get('body')
            ->addModelTransformer(new CallbackTransformer(
               function ($bodyFromDB) {
                   return str_replace('**собака**', 'собака', $bodyFromDB);
               },
               function ($bodyFromInput) {
                   return str_replace('собака', '**собака**', $bodyFromInput);
               },
            ))
        ;
        
        $builder->get('keywords')
            ->addModelTransformer(new CallbackTransformer(
                function ($keywordsFromDB) {
    
                    return $keywordsFromDB
                        ? implode(', ', $keywordsFromDB)
                        : null;
                },
                function ($keywordsFromInput) {
                    $pieces = explode( ',', $keywordsFromInput);
                    array_walk($pieces, function (&$item) {
                       $item = trim($item);
                    });
                    return $pieces;
                },
            ))
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
