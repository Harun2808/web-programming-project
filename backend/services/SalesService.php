<?php
require_once __DIR__ . '/../dao/SalesDAO.php';

class SalesService {
    private $salesDAO;

    public function __construct() {
        $this->salesDAO = new SalesDAO();
    }

    public function getAll() {
        return $this->salesDAO->getAll();
    }

    public function getById($id) {
        return $this->salesDAO->getById($id);
    }

    public function create($car_id, $customer_id, $employee_id, $sale_date, $price) {
        return $this->salesDAO->create($car_id, $customer_id, $employee_id, $sale_date, $price);
    }

    public function update($id, $car_id, $customer_id, $employee_id, $sale_date, $price) {
        return $this->salesDAO->update($id, $car_id, $customer_id, $employee_id, $sale_date, $price);
    }

    public function delete($id) {
        return $this->salesDAO->delete($id);
    }
}
?>
