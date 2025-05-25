<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthMiddleware {
    public static function authenticate() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            Flight::halt(401, json_encode(['error' => 'Missing Authorization header']));
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            Flight::set('user', (array) $decoded);
        } catch (Exception $e) {
            Flight::halt(401, json_encode(['error' => 'Invalid token']));
        }
    }

    public static function authorize($role) {
        $user = Flight::get('user');
        if ($user['role'] !== $role) {
            Flight::halt(403, json_encode(['error' => 'Access denied']));
        }
    }
}
