<?php
require_once __DIR__ . '/../services/CustomerService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
Flight::route('GET /customers', function () {
    
    $service = new CustomerService();
    Flight::json($service->getAll());
});

Flight::route('GET /customers/@id', function ($id) {

    $service = new CustomerService();
    Flight::json($service->getById($id));
});

Flight::route('POST /customers', function () {
  
    $data = Flight::request()->data->getData();
    $service = new CustomerService();
    Flight::json($service->create(
        $data['name'],
        $data['email'],
        $data['phone']
    ));
});

Flight::route('PUT /customers/@id', function ($id) {
     
    $data = Flight::request()->data->getData();
    $service = new CustomerService();
    Flight::json($service->update(
        $id,
        $data['name'],
        $data['email'],
        $data['phone']
    ));
});

Flight::route('DELETE /customers/@id', function ($id) {

    $service = new CustomerService();
    Flight::json($service->delete($id));
});
