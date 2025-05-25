<?php
require_once __DIR__ . '/../services/SalesService.php';

Flight::route('GET /sales', function () {
    //    AuthMiddleware::authenticate();
    $service = new SalesService();
    Flight::json($service->getAll());
});

Flight::route('GET /sales/@id', function ($id) {
    // AuthMiddleware::authenticate();
    $service = new SalesService();
    Flight::json($service->getById($id));
});

Flight::route('POST /sales', function () {
        // AuthMiddleware::authenticate();
    $data = Flight::request()->data->getData();
    $service = new SalesService();
    Flight::json($service->create(
        $data['car_id'],
        $data['customer_id'],
        $data['employee_id'],
        $data['sale_date'],
        $data['price']
    ));
});

Flight::route('PUT /sales/@id', function ($id) {
        // AuthMiddleware::authenticate();
    $data = Flight::request()->data->getData();
    $service = new SalesService();
    Flight::json($service->update(
        $id,
        $data['car_id'],
        $data['customer_id'],
        $data['employee_id'],
        $data['sale_date'],
        $data['price']
    ));
});

Flight::route('DELETE /sales/@id', function ($id) {
        // AuthMiddleware::authenticate();
    $service = new SalesService();
    Flight::json($service->delete($id));
});
