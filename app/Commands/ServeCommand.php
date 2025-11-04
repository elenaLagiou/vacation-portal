<?php

namespace Elagiou\VacationPortal\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';

    protected function configure(): void
    {
        $this
            ->setName('serve')
            ->setDescription('Start the local development server (like php artisan serve)')
            ->setHelp('Runs PHP’s built-in development server at http://localhost:8080');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>🚀 Starting PHP development server at http://localhost:8080</info>');

        $command = 'php -S localhost:8080 -t public';
        $output->writeln("<comment>Command:</comment> {$command}");

        passthru($command);

        return Command::SUCCESS;
    }
}
