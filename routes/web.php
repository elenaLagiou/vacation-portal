<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

use Elagiou\VacationPortal\Controllers\ManagerController;
use Elagiou\VacationPortal\Controllers\EmployeeController;
use Elagiou\VacationPortal\Controllers\AuthController;

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
$authController     = new AuthController($authService);

// Middleware
$authMiddleware = new AuthMiddleware($authService);

// Dispatcher
return simpleDispatcher(function (RouteCollector $r) use ($managerController, $employeeController, $authController, $authMiddleware) {

    //  AUTH ROUTES
    $r->addRoute('GET', '/login', [$authController, 'showLoginForm']);
    $r->addRoute('POST', '/login', fn() => $authController->login($_POST));
    $r->addRoute('GET', '/logout', [$authController, 'logout']);

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
        $r->addRoute(
            'GET',
            '/update-user',
            fn() =>
            Middleware::handle(
                [$managerController, 'showUpdateUserForm'],
                [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]
            )
        );
        $r->addRoute(
            'POST',
            '/update-user',
            fn() =>
            Middleware::handle(
                fn() => $managerController->updateUser($_POST),
                [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]
            )
        );

        $r->addRoute(
            'POST',
            '/delete-user',
            fn() =>
            Middleware::handle(
                [$managerController, 'deleteUser'],
                [$authMiddleware->handle(...), $authMiddleware->managerOnly(...)]
            )
        );

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
            '/request/approve/{id:\d+}',
            fn($id) =>
            Middleware::handle([$managerController, 'approveRequest'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ], [$id])
        );

        $r->addRoute(
            'POST',
            '/request/reject/{id:\d+}',
            fn($id) =>
            Middleware::handle([$managerController, 'rejectRequest'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ], [$id])
        );

        $r->addRoute(
            'POST',
            '/request/delete/{id:\d+}',
            fn($id) =>
            Middleware::handle([$managerController, 'deleteRequest'], [
                $authMiddleware->handle(...),
                $authMiddleware->managerOnly(...)
            ], [$id])
        );
    });

    // EMPLOYEE ROUTES
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
            Middleware::handle(fn() => $employeeController->create($_POST), [
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
