<?php

namespace Src\App\Storage;

class SessionStorage
{
    public function __construct()
    {
        session_start();
    }

    public function get($key)
    {
        return $_SESSION[$key];
    }

    public function set($key, $value): void
    {
        is_object($value) ? $_SESSION[$key] = serialize($value) : $_SESSION[$key] = $value;
    }

    public function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function all(): array
    {
        return $_SESSION;
    }

    public function delete()
    {
        session_destroy();
    }
}