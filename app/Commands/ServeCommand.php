<?php

namespace Elagiou\VacationPortal\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("<info>Starting development server at http://localhost:8080</info>");
        passthru('php -S localhost:8080 -t public');
        return Command::SUCCESS;
    }
}
