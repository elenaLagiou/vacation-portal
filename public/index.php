<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/bootstrap.php';

use Elagiou\VacationPortal\Helpers\Router;

// Load web routes
$dispatcher = require __DIR__ . '/../routes/web.php';

// Dispatch the request
Router::dispatch($dispatcher);
