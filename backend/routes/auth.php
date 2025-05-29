<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
require_once __DIR__ . '/../dao/UserDAO.php';

// Register route
Flight::route('POST /register', function () {
    $data = Flight::request()->data->getData();
    $userDAO = new UserDAO();

    if (!isset($data['email'], $data['password'], $data['name'])) {
        Flight::halt(400, json_encode(['error' => 'Missing required fields']));
    }

    if ($userDAO->findByEmail($data['email'])) {
        Flight::halt(400, json_encode(['error' => 'Email already registered']));
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'] ?? 'sales';

    $userDAO->register($data['name'], $data['email'], $hashedPassword, $role);

    Flight::json(['message' => 'User registered successfully']);
});

// Login route
Flight::route('POST /login', function () {
    $data = Flight::request()->data->getData();
    $userDAO = new UserDAO();
    $user = $userDAO->findByEmail($data['email']);

    if (!$user || !password_verify($data['password'], $user['password'])) {
        Flight::halt(401, json_encode(['error' => 'Invalid credentials']));
    }

    $payload = [
        'id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'exp' => time() + (60 * 60 * 2) // 2 hours
    ];

    $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

    Flight::json([
        'token' => $jwt,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
});
