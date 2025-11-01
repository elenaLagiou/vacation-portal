<?php

namespace Elagiou\VacationPortal\Commands;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunTablesCommand extends Command
{
    protected static $defaultName = 'migrate';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        require __DIR__ . '/../../config/bootstrap.php';
        $pdo = $pdo ?? null;

        $migrationPath = __DIR__ . '/../../database/migrations';
        $files = glob($migrationPath . '/*.sql');

        foreach ($files as $file) {
            $output->writeln("Running: " . basename($file));
            $sql = file_get_contents($file);
            if (trim($sql)) {
                $pdo->exec($sql);
            }
        }

        $output->writeln('<info>Migrations completed successfully!</info>');
        return Command::SUCCESS;
    }
}
