<?php

namespace Elagiou\VacationPortal\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;

class RunTablesCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected static $defaultName = 'db:migrate';

    protected function configure(): void
    {
        $this
            ->setDescription('Run all SQL migration and seeder files.')
            ->setHelp('Executes all .sql files from database/migrations and database/seeders.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("<info>🚀 Running database migrations...</info>");

        // load PDO connection
        $pdo = require __DIR__ . '/../../config/bootstrap.php';

        // paths
        $migrationsPath = __DIR__ . '/../../database/migrations';
        $seedersPath = __DIR__ . '/../../database/seeders';

        $this->runSqlFiles($pdo, $migrationsPath, $output, 'Migration');
        $this->runSqlFiles($pdo, $seedersPath, $output, 'Seeder');

        $output->writeln("<info>✅ All migrations and seeders completed successfully!</info>");
        return Command::SUCCESS;
    }

    private function runSqlFiles(PDO $pdo, string $path, OutputInterface $output, string $type): void
    {
        if (!is_dir($path)) {
            $output->writeln("<comment>⚠️ Directory not found: {$path}</comment>");
            return;
        }

        foreach (glob($path . '/*.sql') as $file) {
            $output->writeln("➡️ Running {$type}: " . basename($file));
            $sql = trim(file_get_contents($file));

            if (!empty($sql)) {
                try {
                    $pdo->exec($sql);
                } catch (\PDOException $e) {
                    $output->writeln("<error>❌ Error running {$file}: {$e->getMessage()}</error>");
                }
            }
        }
    }
}
