<?php

namespace App\Libraries;

class Cookie
{
    public function set(string $key, string $value, int $expiration = 24): void
    {
        $appHost = parse_url($_ENV['APP_URL'], PHP_URL_HOST);
        setcookie($key, $value, time() + $expiration * 3600, '/', $appHost, false, false);
    }
    public function get(string $key): string
    {
        return $_COOKIE[$key] ?? null;
    }
    public function delete(string $key)
    {
        $cookie = $_COOKIE[$key] ?? null;
        if (null !== $cookie) {
            $this->set('AUTH_TOKEN', '', -1);
        }
    }
}