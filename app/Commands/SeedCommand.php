<?php

namespace Elagiou\VacationPortal\Commands;

use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedCommand extends Command
{
    protected static $defaultName = 'db:seed';
    protected static $defaultDescription = 'Run all SQL seeders from database/seeders';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        require __DIR__ . '/../../config/bootstrap.php';
        global $pdo;

        $seedPath = __DIR__ . '/../../database/seeders';
        if (!is_dir($seedPath)) {
            $output->writeln('<error>No seeders directory found at: ' . $seedPath . '</error>');
            return Command::FAILURE;
        }

        $output->writeln('<info>Running seeders from: database/seeders</info>');

        $files = glob($seedPath . '/*.sql');
        sort($files);

        if (empty($files)) {
            $output->writeln('<comment>No seed files found.</comment>');
            return Command::SUCCESS;
        }

        foreach ($files as $file) {
            $output->writeln('→ Running seeder: ' . basename($file));
            $sql = trim(file_get_contents($file));

            if (empty($sql)) {
                $output->writeln('<comment>⚠️ Skipped empty file: ' . basename($file) . '</comment>');
                continue;
            }

            try {
                $pdo->exec($sql);
                $output->writeln('<info>✅ ' . basename($file) . ' executed successfully.</info>');
            } catch (PDOException $e) {
                $output->writeln('<error>❌ Error in ' . basename($file) . ': ' . $e->getMessage() . '</error>');
            }
        }

        $output->writeln('<info>🎉 All seeders executed successfully.</info>');
        return Command::SUCCESS;
    }
}
