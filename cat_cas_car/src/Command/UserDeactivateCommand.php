<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:deactivate',
    description: 'Add a short description for your command',
)]
class UserDeactivateCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    
    public function __construct(
        string $name = null,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Идентификатор пользователя')
            ->addOption('reverse', 'r', InputOption::VALUE_NONE, 'Активирует указанного пользователя');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = $input->getArgument('id');
        $isRevers = $input->getOption('reverse');
        
        $user = $this->userRepository->findOneBy([
            'id' => $id
        ]);
        
        if (empty($user)) {
            throw new Exception(sprintf('Пользователь с ID - %s - не найден', $id));
        }
    
        $isActive = $isRevers ? true : false;
        $user->setIsActive($isActive);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $action = $isActive ? 'Активирован' : 'Деактивирован';
        $io->write(sprintf("%s пользователь с ID - %s\n", $action, $id));
    
        return Command::SUCCESS;
    }
}
