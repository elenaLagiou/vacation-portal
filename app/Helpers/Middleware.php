<?php
namespace Elagiou\VacationPortal\Helpers;

class Middleware
{
    public static function handle(callable $handler, array $middlewares = [])
    {
        foreach ($middlewares as $middleware) {
            $result = $middleware();
            if ($result === false) {
                exit; // stop execution if middleware fails
            }
        }

        return $handler();
    }
}
