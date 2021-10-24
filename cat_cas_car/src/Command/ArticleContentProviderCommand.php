<?php

namespace App\Command;

use App\Homework\ArticleContentProviderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:article:content_provider',
    description: 'Генерирует markdown для статьи через cli',
)]
class ArticleContentProviderCommand extends Command
{
    private ArticleContentProviderInterface $contentProvider;
    
    public function __construct(ArticleContentProviderInterface $contentProvider)
    {
        $this->contentProvider = $contentProvider;
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('paragraphs', InputArgument::REQUIRED, 'Количество параграфов для генерации (целое)')
            ->addOption('word', '-w', InputOption::VALUE_OPTIONAL, 'Слово для добавления')
            ->addOption('wordsCount', '-c', InputOption::VALUE_OPTIONAL, 'Количество повторений слова')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $paragraphs = $input->getArgument('paragraphs');
    
        $paragraphs = intval($paragraphs);
        if ($paragraphs) {
            $io->write(sprintf("Будет сгенерировано следующее количество параграфов -  %s.\n", $paragraphs));
        }
        
        $word = $input->getOption('word');
        $wordsCount = (int)$input->getOption('wordsCount') ?? 0;
        
        $markdown = $this->contentProvider->get($paragraphs, $word, $wordsCount);
        $io->title("Результат генерации:\n");
        $io->write($markdown . "\n");
        
        return Command::SUCCESS;
    }
}
