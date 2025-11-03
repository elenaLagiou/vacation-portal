<?php

namespace Elagiou\VacationPortal\Helpers;

class SessionFlash
{
    /**
     * Set a flash message
     */
    public static function set(string $key, string|array $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['_flash'][$key] = $message;
    }

    /**
     * Get and remove a flash message
     */
    public static function get(string $key): string|array|null
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }
        $message = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $message;
    }
}
