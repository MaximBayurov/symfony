<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:admin-statistic-report',
    description: 'Генерит .csv файл с отчетом и отправляет на указанный email',
)]
class AdminStatisticReportCommand extends Command
{
    private UserRepository $userRepository;
    private ArticleRepository $articleRepository;
    private Mailer $mailer;
    
    public function __construct(
        UserRepository $userRepository,
        ArticleRepository $articleRepository,
        Mailer $mailer,
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->mailer = $mailer;
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'email получателя')
            ->addOption('dateFrom', 'f', InputOption::VALUE_OPTIONAL, 'Дата начала периода, по умолчанию: "-1 неделя"')
            ->addOption('dateTo', 't', InputOption::VALUE_OPTIONAL, 'Дата окончания периода, по умолчанию: "сегодня"')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        if ($email) {
            $io->note(sprintf('Отчёт будет отправлен на email: %s', $email));
        }

        $dateFrom = $this->getDateFromString($input->getOption('dateFrom'), '-1 week');
        $dateTo = $this->getDateFromString($input->getOption('dateTo'), 'now');
        
        $allUsersCount = $this->userRepository->getAllUsersCount();
        $createdArticlesCount = $this->articleRepository->getCreatedCount($dateFrom, $dateTo);
        $publishedArticlesCount = $this->articleRepository->getPublishedCount($dateFrom, $dateTo);
        
        $period = $dateFrom->format('d.m.Y') . '-' . $dateTo->format('d.m.Y');
        $list = [
            [
                'Период', 'Всего пользователей', 'Статей создано за период', 'Статей опубликовано за период'
            ],
            [
                $period,
                $allUsersCount,
                $createdArticlesCount,
                $publishedArticlesCount,
            ]
        ];
        
        $filename = "report_for_".$period."_period.csv";
        $fp = fopen($filename, "w");
        foreach ($list as $line)
        {
            fputcsv(
                $fp, // The file pointer
                $line, // The fields
                ',' // The delimiter
            );
        }
        fclose($fp);
        
        $this->mailer->sendReport($email, $filename);
        
        unlink($filename);
        
        return Command::SUCCESS;
    }
    
    private function getDateFromString(?string $dateFrom, string $default): \DateTime
    {
        if ($dateFrom) {
            return \DateTime::createFromFormat('d.m.Y', $dateFrom);
        } else {
            return new \DateTime($default);
        }
    }
}
