<?php
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

use Elagiou\VacationPortal\Controllers\ManagerController;
use Elagiou\VacationPortal\Repositories\AuthRepository;
use Elagiou\VacationPortal\Repositories\UserRepository;
use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Services\UserService;
use Elagiou\VacationPortal\Middleware\AuthMiddleware;
use Elagiou\VacationPortal\Helpers\Middleware;

// Repositories & Services
$authRepo = new AuthRepository($pdo);
$userRepo = new UserRepository($pdo);
$authService = new AuthService($authRepo);
$userService = new UserService($userRepo);

// Controller & Middleware
$managerController = new ManagerController($authService, $userService);
$authMiddleware = new AuthMiddleware($authService);

// Dispatcher
return simpleDispatcher(function(RouteCollector $r) use ($managerController, $authMiddleware) {
    $r->addRoute('GET', '/login', [$managerController, 'showLoginForm']);
    $r->addRoute('POST', '/login', fn() => $managerController->login($_POST));

    $r->addGroup('/manager', function(RouteCollector $r) use ($managerController, $authMiddleware) {
        $r->addRoute('GET', '/home', fn() =>
            Middleware::handle([$managerController, 'home'], [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]));
        $r->addRoute('POST', '/create-user', fn() =>
            Middleware::handle([$managerController, 'createUser'], [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]));
    });

    $r->addRoute('GET', '/logout', [$managerController, 'logout']);
});
