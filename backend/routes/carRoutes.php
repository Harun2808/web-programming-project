<?php
require_once __DIR__ . '/../services/CarService.php';

Flight::route('GET /cars', function () {
    $service = new CarService();
    Flight::json($service->getAll());
});

Flight::route('GET /cars/@id', function ($id) {
    $service = new CarService();
    Flight::json($service->getById($id));
});

Flight::route('POST /cars', function () {
    $data = Flight::request()->data->getData();
    $service = new CarService();
    Flight::json($service->create(
        $data['make'],
        $data['model'],
        $data['year'],
        $data['price'],
        $data['status']
    ));
});

Flight::route('PUT /cars/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new CarService();
    Flight::json($service->update(
        $id,
        $data['make'],
        $data['model'],
        $data['year'],
        $data['price'],
        $data['status']
    ));
});

Flight::route('DELETE /cars/@id', function ($id) {
    $service = new CarService();
    Flight::json($service->delete($id));
});
