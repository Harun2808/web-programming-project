<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private $secret;

    public function __construct() {
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function generateToken($payload) {
        $issuedAt = time();
        $expire = $issuedAt + 3600; // 1 hour

        $token = [
            "iat" => $issuedAt,
            "exp" => $expire,
            "data" => $payload
        ];

        return JWT::encode($token, $this->secret, 'HS256');
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return null;
        }
    }
}
