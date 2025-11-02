<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

use Elagiou\VacationPortal\Controllers\ManagerController;
use Elagiou\VacationPortal\Controllers\EmployeeController;
use Elagiou\VacationPortal\Repositories\AuthRepository;
use Elagiou\VacationPortal\Repositories\UserRepository;
use Elagiou\VacationPortal\Repositories\VacationRepository;
use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Services\UserService;
use Elagiou\VacationPortal\Services\VacationService;
use Elagiou\VacationPortal\Middleware\AuthMiddleware;
use Elagiou\VacationPortal\Helpers\Middleware;

// Repositories
$authRepo      = new AuthRepository($pdo);
$userRepo      = new UserRepository($pdo);
$vacationRepo  = new VacationRepository($pdo);

// Services
$authService     = new AuthService($authRepo);
$userService     = new UserService($userRepo);
$vacationService = new VacationService($vacationRepo);

// Controllers
$managerController  = new ManagerController($authService, $userService, $vacationService);
$employeeController = new EmployeeController($authService, $vacationService);

// Middleware
$authMiddleware = new AuthMiddleware($authService);

// Dispatcher
return simpleDispatcher(function (RouteCollector $r) use ($managerController, $employeeController, $authMiddleware) {

    //  AUTH ROUTES
    $r->addRoute('GET', '/login', [$managerController, 'showLoginForm']);
    $r->addRoute('POST', '/login', fn() => $managerController->login($_POST));
    $r->addRoute('GET', '/logout', [$managerController, 'logout']);

    // MANAGER ROUTES
    $r->addGroup('/manager', function (RouteCollector $r) use ($managerController, $authMiddleware) {

        $r->addRoute(
            'GET',
            '/home',
            fn() =>
            Middleware::handle([$managerController, 'home'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ])
        );
        $r->addRoute(
            'GET',
            '/create-user',
            fn() =>
            Middleware::handle([$managerController, 'showCreateUserForm'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ])
        );

        $r->addRoute(
            'POST',
            '/create-user',
            fn() =>
            Middleware::handle(
                fn() => $managerController->createUser($_POST), // ✅ pass $_POST here
                [
                    $authMiddleware->handle(...),
                    $authMiddleware->managerOnly(...)
                ]
            )
        );
        $r->addRoute('GET', '/manager/update-user', fn() =>
        Middleware::handle([$managerController, 'showUpdateUserForm'], [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]));
        $r->addRoute('POST', '/manager/update-user', fn() =>
        Middleware::handle([$managerController, 'updateUser'], [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]));
        $r->addRoute('POST', '/manager/delete-user', fn() =>
        Middleware::handle([$managerController, 'deleteUser'], [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]));

        // Vacation request management
        $r->addRoute(
            'GET',
            '/requests',
            fn() =>
            Middleware::handle([$managerController, 'listRequests'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ])
        );

        $r->addRoute(
            'POST',
            '/request/approve/{id}',
            fn($id) =>
            Middleware::handle([$managerController, 'approveRequest'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ], [$id])
        );

        $r->addRoute(
            'POST',
            '/request/reject/{id}',
            fn($id) =>
            Middleware::handle([$managerController, 'rejectRequest'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ], [$id])
        );
    });

    // ---------------------------
    // 👷 EMPLOYEE ROUTES
    // ---------------------------
    $r->addGroup('/employee', function (RouteCollector $r) use ($employeeController, $authMiddleware) {

        // Employee dashboard
        $r->addRoute(
            'GET',
            '/home',
            fn() =>
            Middleware::handle([$employeeController, 'home'], [
                $authMiddleware->handle(...),
                $authMiddleware->employeeOnly(...)
            ])
        );

        // View vacation requests
        $r->addRoute(
            'GET',
            '/requests',
            fn() =>
            Middleware::handle([$employeeController, 'listRequests'], [
                $authMiddleware->handle(...),
                $authMiddleware->employeeOnly(...)
            ])
        );

        // Create new vacation request
        $r->addRoute(
            'GET',
            '/request/create',
            fn() =>
            Middleware::handle([$employeeController, 'createForm'], [
                $authMiddleware->handle(...),
                $authMiddleware->employeeOnly(...)
            ])
        );

        $r->addRoute(
            'POST',
            '/request/create',
            fn() =>
            Middleware::handle([$employeeController, 'create'], [
                $authMiddleware->handle(...),
                $authMiddleware->employeeOnly(...)
            ])
        );

        // Delete a pending request
        $r->addRoute(
            'POST',
            '/request/delete/{id}',
            fn($id) =>
            Middleware::handle([$employeeController, 'delete'], [
                $authMiddleware->handle(...),
                $authMiddleware->employeeOnly(...)
            ], [$id])
        );
    });
});
