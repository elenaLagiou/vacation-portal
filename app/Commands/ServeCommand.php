<?php

namespace Elagiou\VacationPortal\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{
    // ✅ define the command name so Symfony recognizes it
    protected static $defaultName = 'serve';

    protected function configure(): void
    {
        $this
            ->setDescription('Start the local development server (like php artisan serve)')
            ->setHelp('Runs PHPs built-in development server at http://localhost:8080');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>🚀 Starting PHP development server at http://localhost:8080</info>');

        $command = 'php -S localhost:8080 -t public';
        $output->writeln("<comment>Command:</comment> {$command}");

        // Run built-in PHP server
        passthru($command);

        return Command::SUCCESS;
    }
}
