<?php

namespace App\Libraries;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    public static function decode($token)
    {
        try {
            $key = new Key($_ENV['SECRET_KEY'], 'HS512');
            return JWT::decode($token, $key);
        } catch (Exception $exception) {
            return false;
        }
    }
    public static function encode($payload, $expiration = 1440)
    {
        $payload = [
            ...$payload,
            'exp' => Carbon::now()->addSeconds($expiration * 60)->unix()
        ];
        return JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS512');
    }
}