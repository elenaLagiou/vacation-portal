<?php
namespace Elagiou\VacationPortal\Helpers;

class Middleware
{
    public static function handle(callable $handler, array $middlewares = [], array $args = [])
    {
        foreach ($middlewares as $middleware) {
            $result = $middleware();
            if ($result === false) {
                exit; // stop execution if middleware fails
            }
        }

        return call_user_func_array($handler, $args);
    }
}
