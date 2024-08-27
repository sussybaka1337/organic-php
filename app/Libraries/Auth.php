<?php

namespace App\Libraries;
use App\Libraries\JWTHelper;

class Auth
{
    public function user()
    {
        $authPrefix = $_ENV['AUTH_PREFIX'];
        $token = $_COOKIE[$authPrefix];
        return JWTHelper::decode($token);
    }
    public function login(array $payload)
    {
        $token = JWTHelper::encode($payload);
        $authPrefix = $_ENV['AUTH_PREFIX'];
        cookie()->set($authPrefix, $token);
    }
}