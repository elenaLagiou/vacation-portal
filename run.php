<?php
require __DIR__ . '/app/Config/bootstrap.php';

use Elagiou\VacationPortal\Commands\RunTablesCommand;

$command = $argv[1] ?? null;

if ($command === 'run:tables') {
    $runner = new RunTablesCommand($pdo);
    $runner->handle();
} else {
    echo "Available commands:\n";
    echo "  php run.php run:tables   # Run all migrations and seeders\n";
}
