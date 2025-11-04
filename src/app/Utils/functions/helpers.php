<?php

use Dotenv\Dotenv;

function loadEnvIfPresent(): void {
    static $loaded = false;
    if ($loaded) {
        return;
    }

    $candidateDirs = array_filter([
        getcwd(),
        dirname(__DIR__, 4), // project root when running from source tree
        dirname(__FILE__, 4), // fallback when running from a packaged path
        $_SERVER['PWD'] ?? null,
    ]);

    foreach ($candidateDirs as $dir) {
        if (is_string($dir) && is_dir($dir) && file_exists($dir . '/.env')) {
            Dotenv::createImmutable($dir)->safeLoad();
            $loaded = true;
            return;
        }
    }

    // Mark as loaded to avoid repeated scans when no .env is present
    $loaded = true;
}

function env(string $key, $default = null) {
    loadEnvIfPresent();
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null) {
        return $default;
    }
    return $value;
}