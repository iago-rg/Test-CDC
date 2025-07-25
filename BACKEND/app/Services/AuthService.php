<?php

namespace App\Services;

use Firebase\JWT\JWT;

class AuthService
{
    public function JWT($data)
    {
        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = $iat + 3600;

        $payload = [
            'iat' => $iat,
            'exp' => $exp,
            'email' => $data['email'],
            'id' => $data['id'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $token;
    }
}
