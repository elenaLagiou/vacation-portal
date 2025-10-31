<?php

namespace Elagiou\VacationPortal\Helpers;

class View
{
    /**
     * Render a PHP view file with optional data.
     */
    public static function render(string $path, array $data = []): void
    {
        $relativePath = str_replace('.', '/', $path);
        $baseDir = __DIR__ . '/../../resources/views/';
        $filePath = $baseDir . $relativePath . '.php';

        if (!file_exists($filePath)) {
            $pluralPath = preg_replace('/manager\//', 'managers/', $relativePath, 1);
            $filePath = $baseDir . $pluralPath . '.php';
        }

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "❌ View not found: {$path}";
            return;
        }

        extract($data, EXTR_SKIP);
        require $filePath;
    }
}

/**
 * Global view() helper (like Laravel)
 */
if (!function_exists('view')) {
    function view(string $path, array $data = []): void
    {
        \Elagiou\VacationPortal\Helpers\View::render($path, $data);
    }
}
