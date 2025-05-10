<?php
require_once __DIR__ . '/../services/EmployeeService.php';

Flight::route('GET /employees', function () {
    $service = new EmployeeService();
    Flight::json($service->getAll());
});

Flight::route('GET /employees/@id', function ($id) {
    $service = new EmployeeService();
    Flight::json($service->getById($id));
});

Flight::route('POST /employees', function () {
    $data = Flight::request()->data->getData();
    $service = new EmployeeService();
    Flight::json($service->create(
        $data['user_id'],
        $data['name'],
        $data['position'],
        $data['contact'],
        $data['status'] ?? 'active'
    ));
});

Flight::route('PUT /employees/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new EmployeeService();
    Flight::json($service->update(
        $id,
        $data['user_id'],
        $data['name'],
        $data['position'],
        $data['contact'],
        $data['status']
    ));
});

Flight::route('DELETE /employees/@id', function ($id) {
    $service = new EmployeeService();
    Flight::json($service->delete($id));
});
