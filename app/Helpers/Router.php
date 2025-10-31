<?php

namespace Elagiou\VacationPortal\Helpers;

use FastRoute\Dispatcher;

class Router
{
    public static function dispatch(Dispatcher $dispatcher): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo '404 Not Found';
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo '405 Method Not Allowed';
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                if (is_callable($handler)) {
                    call_user_func_array($handler, $vars);
                } elseif (is_array($handler)) {
                    [$class, $method] = $handler;
                    call_user_func_array([$class, $method], $vars);
                }
                break;
        }
    }
}
