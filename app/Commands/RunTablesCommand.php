<?php

namespace Elagiou\VacationPortal\Commands;

class RunTablesCommand
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function handle(): void
    {
        echo "➡ Running database migrations...\n";
        $this->runSqlFiles(__DIR__ . '/../../database/migrations');

        echo "➡ Running seeders...\n";
        $this->runSqlFiles(__DIR__ . '/../../database/seeders');

        echo "✅ Migrations and seeders executed successfully.\n";
    }

    private function runSqlFiles(string $folder): void
    {
        $files = glob($folder . '/*.sql');
        sort($files); // Ensure execution order

        foreach ($files as $file) {
            echo "Running: " . basename($file) . "...\n";
            $sql = file_get_contents($file);

            try {
                $this->pdo->exec($sql);
            } catch (\PDOException $e) {
                echo "❌ Error in file " . basename($file) . ": " . $e->getMessage() . "\n";
                exit(1);
            }
        }
    }
}
