<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:weekly-newsletter',
    description: 'Еженедельная рассылка статей',
)]
class WeeklyNewsletterCommand extends Command
{
    
    private UserRepository $userRepository;
    private ArticleRepository $articleRepository;
    private Mailer $mailer;
    
    public function __construct(
        UserRepository $userRepository,
        ArticleRepository $articleRepository,
        Mailer $mailer
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->mailer = $mailer;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var User[] $users */
        $users = $this->userRepository->findAllSubscribed();
        /** @var Article[] $articles */
        $articles = $this->articleRepository->findAllPublishedLastWeek();
        
        $io = new SymfonyStyle($input, $output);
        
        if (count($articles) == 0) {
            $io->warning('Нет новых публикаций за прошедшую неделю');
            return Command::SUCCESS;
        }
        
        $io->progressStart(count($users));
        foreach ($users as $user) {
            
            $this->mailer->sendWeeklyNewsletter($user, $articles);
            
            $io->progressAdvance();
            break;
        }
        $io->progressFinish();
        
        return Command::SUCCESS;
    }
}
