#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

// Import your custom commands
use Elagiou\VacationPortal\Commands\RunTablesCommand;
use Elagiou\VacationPortal\Commands\SeedCommand;
use Elagiou\VacationPortal\Commands\ServeCommand;

$app = new Application('Vacation Portal CLI', '1.0.0');

// Register commands
$app->add(new RunTablesCommand());
$app->add(new ServeCommand());
$app->add(new SeedCommand());

$app->run();
