<?php

use Dotenv\Dotenv;

function env(string $key, $default = null) {
    static $loaded = false;
    if (!$loaded) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
        $dotenv->safeLoad();
        $loaded = true;
    }

    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return $value;
}