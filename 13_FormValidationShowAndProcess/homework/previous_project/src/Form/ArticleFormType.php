<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Homework\ArticleWordsFilter;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ArticleWordsFilter
     */
    private ArticleWordsFilter $articleWordsFilter;

    public function __construct(UserRepository $userRepository, ArticleWordsFilter $articleWordsFilter)
    {
        $this->userRepository = $userRepository;
        $this->articleWordsFilter = $articleWordsFilter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Название статьи',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание статьи',
                'attr' => [
                    'rows' => 3,
                ],
            ])
            ->add('body', null, [
                'label' => 'Текст статьи',
                'attr' => [
                    'rows' => 10,
                ],
            ])
            ->add('publishedAt', null, [
                'label' => 'Дата публикации статьи',
                'widget' => 'single_text',
            ])
            ->add('keywords', null, [
                'label' => 'Ключевые слова статьи',
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choices' => $this->userRepository->findAllSortedByName(),
                'choice_label' => function (User $user) {
                    return sprintf('%s (id: %d)', $user->getFirstName(), $user->getId());
                },
                'label' => 'Автор статьи',
                'placeholder' => 'Выберите автора статьи',
            ])
        ;

        $stopWords = ['стакан', 'бокал'];

        $builder->get('body')
            ->addModelTransformer(new CallbackTransformer(
                function ($bodyFromDatabase) {
                    return $bodyFromDatabase;
                },
                function ($bodyFromInput) use ($stopWords) {
                    return $this->articleWordsFilter->filter($bodyFromInput, $stopWords);
                }
            ))
        ;
        $builder->get('description')
            ->addModelTransformer(new CallbackTransformer(
                function ($bodyFromDatabase) {
                    return $bodyFromDatabase;
                },
                function ($bodyFromInput) use ($stopWords) {
                    return $this->articleWordsFilter->filter($bodyFromInput, $stopWords);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
