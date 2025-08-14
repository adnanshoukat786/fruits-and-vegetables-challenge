<?php

namespace App\Command;

use App\Service\StorageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:load-data', description: 'Load data from request.json')]
class LoadDataCommand extends Command
{
    public function __construct(private StorageService $storageService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->storageService->processJsonFile();
            $io->success('Data loaded successfully from request.json!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Failed to load data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}