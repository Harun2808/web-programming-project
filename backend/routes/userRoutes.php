<?php
require_once __DIR__ . '/../services/UserService.php';

Flight::route('GET /users', function () {
    $service = new UserService();
    $users = $service->getAllUsers();
    Flight::json($users);
});

Flight::route('GET /users/@id', function ($id) {
    $service = new UserService();
    $user = $service->getUserById($id);
    Flight::json($user);
});

Flight::route('POST /users', function () {
    $data = Flight::request()->data->getData();  
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];
    $role = $data['role'];

    $service = new UserService();
    $created = $service->createUser($name, $email, $password, $role);  
    Flight::json(['success' => $created]);
});


Flight::route('PUT /users/@id', function ($id) {
    $data = Flight::request()->data->getData();  
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];
    $role = $data['role'];

    $service = new UserService();
    $updated = $service->updateUser($id, $name, $email, $password, $role);  
    Flight::json(['success' => $updated]);
});


Flight::route('DELETE /users/@id', function ($id) {
    $service = new UserService();
    $deleted = $service->deleteUser($id);
    Flight::json(['success' => $deleted]);
});
