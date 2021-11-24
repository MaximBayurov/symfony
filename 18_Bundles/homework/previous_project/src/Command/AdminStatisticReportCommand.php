<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminStatisticReportCommand extends Command
{
    protected static $defaultName = 'app:admin-statistic-report';
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, Mailer $mailer)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Отправка отчета о статистике на сайте')
            ->addArgument('email', InputArgument::REQUIRED, 'Email получателя')
            ->addOption('dateFrom', null, InputOption::VALUE_OPTIONAL, 'Дата начала периода', '-1 week')
            ->addOption('dateTo', null, InputOption::VALUE_OPTIONAL, 'Дата окончания периода', 'now')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $dateFrom = new DateTime($input->getOption('dateFrom'));
        $dateTo = new DateTime($input->getOption('dateTo'));

        $user = (new User())->setFirstName('')->setEmail($email);

        $filePath = sys_get_temp_dir() . '/report.csv';
        
        $fp = fopen($filePath, 'w');
        fputcsv($fp, ['Период', 'Всего пользователей', 'Статей создано за период', 'Статей опубликовано за период'], ';');
        fputcsv($fp, [
            $dateFrom->format('d.m.Y') . ' - ' . $dateTo->format('d.m.Y'),
            $this->userRepository->count([]),
            $this->articleRepository->countPublishedByPeriod($dateFrom, $dateTo),
            $this->articleRepository->countByPeriod($dateFrom, $dateTo),
        ], ';');
        fclose($fp);

        $this->mailer->sendAdminStatisticsReport($user, $filePath);

        return 0;
    }
}
