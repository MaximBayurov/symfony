<?php

namespace SymfonySkillbox\HomeworkBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use SymfonySkillbox\HomeworkBundle\UnitFactory;

#[AsCommand(
    name: 'symfony-skillbox-homework:produce-units',
    description: 'Консольная команда, для запуска фабрики создания юнитов.',
)]
class ProduceUnitsCommand extends Command
{
    protected static $defaultName = 'symfony-skillbox-homework:produce-units';
    private UnitFactory $unitFactory;
    
    public function __construct(
        UnitFactory $unitFactory
    ) {
        parent::__construct();
        
        $this->unitFactory = $unitFactory;
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('resources', InputArgument::REQUIRED, 'Resources amount')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $resources = $input->getArgument('resources');
    
        $units = $this->unitFactory->produceUnits($resources);
    
        $table = [];
        foreach ($units as $unit) {
            $table[] = [
                $unit->getName(),
                $unit->getCost(),
                $unit->getStrength(),
                $unit->getHealth(),
            ];
        }
        
        $io->write(sprintf("На %d было куплено %d юнитов\r\n", $input->getArgument('resources'), count($units)));
        $io->table(
            [
                'Имя', 'Цена', 'Сила', 'Здоровье'
            ],
            $table
        );
        $io->write(sprintf("Осталось ресурсов: %d\r\n", $resources));
        
        return Command::SUCCESS;
    }
}
